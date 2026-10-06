<?php

namespace App\Http\Controllers\Instalador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instalador\CuentaRequest;
use App\Http\Requests\Instalador\PerfilRequest;
use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\PerfilPublico;
use App\Models\PlanPrecio;
use App\Models\Psicologa;
use App\Models\Servicio;
use App\Services\Instalador\ConfiguradorBaseDatos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class InstaladorController extends Controller
{
    public function inicio(): RedirectResponse
    {
        return redirect()->route('instalacion.base-datos');
    }

    public function baseDatos()
    {
        if (config('database.default') === 'pgsql') {
            try {
                DB::connection()->getPdo();
                session(['instalacion.db_ready' => true]);

                return redirect()->route('instalacion.cuenta');
            } catch (Throwable $e) {
                return view('instalador.pasos.base-datos', [
                    'paso' => 1,
                    'datos' => [],
                ])->with('error', 'No se pudo conectar con PostgreSQL: '.$e->getMessage());
            }
        }

        return view('instalador.pasos.base-datos', [
            'paso' => 1,
            'datos' => session('instalacion.db', [
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username'),
                'password' => '',
            ]),
        ]);
    }

    public function guardarBaseDatos(Request $request, ConfiguradorBaseDatos $configurador): RedirectResponse
    {
        $datos = $request->validate([
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'numeric'],
            'database' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9_]+$/'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $configurador->probarConexion($datos);
            $configurador->crearBaseDatos($datos);
            $configurador->escribirEnv($datos);
            $configurador->aplicarEnRuntime($datos);
            $configurador->migrar();
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        session(['instalacion.db_ready' => true, 'instalacion.db' => $datos]);

        return redirect()->route('instalacion.cuenta');
    }

    public function cuenta()
    {
        if ($redir = $this->requiereBaseDatos()) {
            return $redir;
        }

        return view('instalador.pasos.cuenta', [
            'paso' => 2,
            'datos' => session('instalacion.cuenta', []),
        ]);
    }

    public function guardarCuenta(CuentaRequest $request): RedirectResponse
    {
        if ($redir = $this->requiereBaseDatos()) {
            return $redir;
        }

        session(['instalacion.cuenta' => $request->validated()]);

        return redirect()->route('instalacion.perfil');
    }

    public function perfil()
    {
        if ($redir = $this->requierePaso('cuenta')) {
            return $redir;
        }

        return view('instalador.pasos.perfil', [
            'paso' => 3,
            'cuenta' => session('instalacion.cuenta'),
            'datos' => session('instalacion.perfil', []),
        ]);
    }

    public function guardarPerfil(PerfilRequest $request): RedirectResponse
    {
        if ($redir = $this->requierePaso('cuenta')) {
            return $redir;
        }

        session(['instalacion.perfil' => $request->validated()]);

        return redirect()->route('instalacion.servicios');
    }

    public function servicios()
    {
        if ($redir = $this->requierePaso('perfil')) {
            return $redir;
        }

        return view('instalador.pasos.servicios', [
            'paso' => 4,
            'datos' => session('instalacion.servicios', []),
        ]);
    }

    public function guardarServicios(Request $request): RedirectResponse
    {
        if ($redir = $this->requierePaso('perfil')) {
            return $redir;
        }

        $datos = $request->validate([
            'servicios' => ['nullable', 'array'],
            'servicios.*.titulo' => ['nullable', 'string', 'max:255'],
            'servicios.*.descripcion' => ['nullable', 'string', 'max:1000'],
            'especialidades' => ['nullable', 'array'],
            'especialidades.*.titulo' => ['nullable', 'string', 'max:255'],
            'especialidades.*.descripcion' => ['nullable', 'string', 'max:1000'],
            'planes' => ['nullable', 'array'],
            'planes.*.modalidad' => ['nullable', Rule::in(['online', 'presencial'])],
            'planes.*.titulo' => ['nullable', 'string', 'max:255'],
            'planes.*.precio' => ['nullable', 'string', 'max:50'],
            'planes.*.caracteristicas' => ['nullable', 'string', 'max:1000'],
        ]);

        session(['instalacion.servicios' => $datos]);

        return redirect()->route('instalacion.apariencia');
    }

    public function apariencia()
    {
        if ($redir = $this->requierePaso('servicios')) {
            return $redir;
        }

        return view('instalador.pasos.apariencia', [
            'paso' => 5,
            'temas' => config('psicocms.temas_disponibles'),
            'temaSeleccionado' => config('psicocms.tema_por_defecto'),
            'modoSeleccionado' => config('psicocms.modo_por_defecto'),
        ]);
    }

    public function finalizar(Request $request, ConfiguradorBaseDatos $configurador)
    {
        if ($redir = $this->requierePaso('servicios')) {
            return $redir;
        }

        $datos = $request->validate([
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'tema' => ['required', Rule::in(config('psicocms.temas_disponibles'))],
            'modo' => ['required', Rule::in(config('psicocms.modos_disponibles'))],
        ]);

        if ($db = session('instalacion.db')) {
            $configurador->aplicarEnRuntime($db);
        }

        $cuenta = session('instalacion.cuenta');
        $perfil = session('instalacion.perfil', []);
        $listas = session('instalacion.servicios', []);

        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            $nombre = 'perfil_'.uniqid().'.'.$request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move(public_path('uploads/perfil'), $nombre);
            $rutaFoto = 'uploads/perfil/'.$nombre;
        }

        DB::transaction(function () use ($cuenta, $perfil, $listas, $datos, $rutaFoto) {
            Psicologa::create([
                'nombre' => $cuenta['nombre'],
                'apellidos' => $cuenta['apellidos'],
                'email_privado' => $cuenta['email'],
                'telefono_privado' => $cuenta['telefono'],
                'password' => $cuenta['password'],
            ]);

            PerfilPublico::create([
                'nombre' => $cuenta['nombre'],
                'apellidos' => $cuenta['apellidos'],
                'eslogan' => $perfil['eslogan'] ?? null,
                'num_colegiado' => $perfil['num_colegiado'] ?? null,
                'telefono_citas' => $perfil['telefono_citas'] ?? null,
                'email_citas' => $perfil['email_citas'] ?? null,
                'sobre_mi' => $perfil['sobre_mi'] ?? null,
                'direccion' => $perfil['direccion'] ?? null,
                'lugar_consulta' => $perfil['lugar_consulta'] ?? null,
                'foto' => $rutaFoto,
            ]);

            foreach ($this->filas($listas['servicios'] ?? []) as $i => $fila) {
                Servicio::create([
                    'titulo' => $fila['titulo'],
                    'descripcion' => $fila['descripcion'] ?? null,
                    'orden' => $i,
                ]);
            }

            foreach ($this->filas($listas['especialidades'] ?? []) as $i => $fila) {
                Especialidad::create([
                    'titulo' => $fila['titulo'],
                    'descripcion' => $fila['descripcion'] ?? null,
                    'orden' => $i,
                ]);
            }

            foreach ($this->filas($listas['planes'] ?? []) as $i => $fila) {
                PlanPrecio::create([
                    'modalidad' => $fila['modalidad'] ?? 'online',
                    'titulo' => $fila['titulo'],
                    'precio' => $fila['precio'] ?? null,
                    'caracteristicas' => $fila['caracteristicas'] ?? null,
                    'orden' => $i,
                ]);
            }

            ConfiguracionWeb::guardar('tema_activo', $datos['tema']);
            ConfiguracionWeb::guardar('modo', $datos['modo']);
        });

        $request->session()->forget('instalacion');

        return view('instalador.completado', [
            'nombre' => $cuenta['nombre'],
        ]);
    }

    private function filas(array $filas): array
    {
        return array_values(array_filter($filas, fn ($fila) => ! empty($fila['titulo'])));
    }

    private function requiereBaseDatos(): ?RedirectResponse
    {
        if (! session('instalacion.db_ready')) {
            return redirect()->route('instalacion.base-datos')
                ->with('error', 'Primero configura la conexión a la base de datos.');
        }

        return null;
    }

    private function requierePaso(string $paso): ?RedirectResponse
    {
        if ($redir = $this->requiereBaseDatos()) {
            return $redir;
        }

        if (! session('instalacion.'.$paso)) {
            return redirect()->route('instalacion.'.$paso);
        }

        return null;
    }
}
