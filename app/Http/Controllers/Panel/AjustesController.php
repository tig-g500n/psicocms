<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionWeb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AjustesController extends Controller
{
    public function guardarApariencia(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'apariencia' => ['required', 'in:claro,oscuro'],
            'color' => ['required', Rule::in(array_column(config('psicocms.colores_dashboard'), 'clave'))],
        ]);

        ConfiguracionWeb::guardar('dashboard_apariencia', $datos['apariencia']);
        ConfiguracionWeb::guardar('dashboard_color', $datos['color']);

        return back()->with('exito', 'Apariencia del panel actualizada.');
    }

    public function secciones(): View
    {
        $secciones = config('psicocms.secciones_web', []);

        $estado = [];
        foreach ($secciones as $seccion) {
            $estado[$seccion['clave']] = seccion_activa($seccion['clave']);
        }

        return view('panel.ajustes.secciones', [
            'secciones' => $secciones,
            'estado' => $estado,
        ]);
    }

    public function guardarSecciones(Request $request): RedirectResponse
    {
        $activas = $request->input('secciones', []);

        foreach (config('psicocms.secciones_web', []) as $seccion) {
            ConfiguracionWeb::guardar(
                'seccion_'.$seccion['clave'],
                in_array($seccion['clave'], $activas, true) ? '1' : '0'
            );
        }

        return back()->with('exito', 'Secciones de la web actualizadas.');
    }
}
