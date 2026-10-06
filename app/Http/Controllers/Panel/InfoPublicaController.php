<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\PerfilPublicoRequest;
use App\Models\Especialidad;
use App\Models\PerfilPublico;
use App\Models\PlanPrecio;
use App\Models\Servicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InfoPublicaController extends Controller
{
    public function index(): View
    {
        return view('panel.info-publica.index', [
            'perfil' => PerfilPublico::firstOrCreate([], ['nombre' => '', 'apellidos' => '']),
            'servicios' => Servicio::orderBy('orden')->get(),
            'especialidades' => Especialidad::orderBy('orden')->get(),
            'planes' => PlanPrecio::orderBy('orden')->get(),
        ]);
    }

    public function guardarPerfil(PerfilPublicoRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $perfil = PerfilPublico::firstOrCreate([], ['nombre' => '', 'apellidos' => '']);

        if ($request->hasFile('foto')) {
            $nombre = 'perfil_'.uniqid().'.'.$request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move(public_path('uploads/perfil'), $nombre);
            $datos['foto'] = 'uploads/perfil/'.$nombre;
        } else {
            unset($datos['foto']);
        }

        $perfil->update($datos);

        return back()->with('exito', 'Perfil público actualizado.');
    }

    public function guardarServicios(Request $request): RedirectResponse
    {
        $request->validate([
            'servicios' => ['nullable', 'array'],
            'servicios.*.titulo' => ['nullable', 'string', 'max:255'],
            'servicios.*.descripcion' => ['nullable', 'string', 'max:1000'],
            'servicios.*.icono' => ['nullable', 'string', 'max:100'],
            'servicios.*.activo' => ['nullable'],
        ]);

        Servicio::query()->delete();

        foreach ($this->filas($request->input('servicios', [])) as $i => $fila) {
            Servicio::create([
                'titulo' => $fila['titulo'],
                'descripcion' => $fila['descripcion'] ?? null,
                'icono' => $fila['icono'] ?? null,
                'activo' => ($fila['activo'] ?? '1') === '1',
                'orden' => $i,
            ]);
        }

        return back()->with('exito', 'Servicios actualizados.');
    }

    public function guardarEspecialidades(Request $request): RedirectResponse
    {
        $request->validate([
            'especialidades' => ['nullable', 'array'],
            'especialidades.*.titulo' => ['nullable', 'string', 'max:255'],
            'especialidades.*.descripcion' => ['nullable', 'string', 'max:1000'],
        ]);

        Especialidad::query()->delete();

        foreach ($this->filas($request->input('especialidades', [])) as $i => $fila) {
            Especialidad::create([
                'titulo' => $fila['titulo'],
                'descripcion' => $fila['descripcion'] ?? null,
                'orden' => $i,
            ]);
        }

        return back()->with('exito', 'Especialidades actualizadas.');
    }

    public function guardarPlanes(Request $request): RedirectResponse
    {
        $request->validate([
            'planes' => ['nullable', 'array'],
            'planes.*.modalidad' => ['nullable', Rule::in(['online', 'presencial'])],
            'planes.*.titulo' => ['nullable', 'string', 'max:255'],
            'planes.*.precio' => ['nullable', 'string', 'max:50'],
            'planes.*.caracteristicas' => ['nullable', 'string', 'max:1000'],
        ]);

        PlanPrecio::query()->delete();

        foreach ($this->filas($request->input('planes', [])) as $i => $fila) {
            PlanPrecio::create([
                'modalidad' => $fila['modalidad'] ?? 'online',
                'titulo' => $fila['titulo'],
                'precio' => $fila['precio'] ?? null,
                'caracteristicas' => $fila['caracteristicas'] ?? null,
                'orden' => $i,
            ]);
        }

        return back()->with('exito', 'Planes y precios actualizados.');
    }

    private function filas(array $filas): array
    {
        return array_values(array_filter($filas, fn ($fila) => ! empty($fila['titulo'])));
    }
}
