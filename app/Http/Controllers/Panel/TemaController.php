<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\PerfilPublico;
use App\Models\PlanPrecio;
use App\Models\Servicio;
use App\Services\ThemeManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemaController extends Controller
{
    public function __construct(private ThemeManager $temas)
    {
    }

    public function index(): View
    {
        return view('panel.temas.index', [
            'temas' => $this->temas->todos(),
            'activo' => $this->temas->activo(),
            'modo' => $this->temas->modoActivo(),
        ]);
    }

    public function activar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'tema' => ['required', 'string'],
            'modo' => ['required', 'in:landing,multipagina'],
        ]);

        if (! $this->temas->existe($datos['tema'])) {
            return back()->with('error', 'El tema seleccionado no existe.');
        }

        ConfiguracionWeb::guardar('tema_activo', $datos['tema']);
        ConfiguracionWeb::guardar('modo', $datos['modo']);

        $nombre = $this->temas->manifiesto($datos['tema'])['nombre'];
        $formato = $datos['modo'] === 'landing' ? 'Landing' : 'Multipágina';

        return back()->with('exito', "Tema «{$nombre}» activado en formato {$formato}.");
    }

    public function preview(string $tema, Request $request): View
    {
        abort_unless($this->temas->existe($tema), 404);

        $modo = $request->query('modo', $this->temas->modoActivo());

        if (! in_array($modo, ['landing', 'multipagina'], true)) {
            $modo = config('psicocms.modo_por_defecto');
        }

        return view('publico.preview', [
            'tema' => $this->temas->manifiesto($tema),
            'modo' => $modo,
            'perfil' => PerfilPublico::first(),
            'servicios' => Servicio::orderBy('orden')->get(),
            'especialidades' => Especialidad::orderBy('orden')->get(),
            'planes' => PlanPrecio::orderBy('orden')->get(),
        ]);
    }
}
