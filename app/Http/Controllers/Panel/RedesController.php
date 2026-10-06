<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionWeb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedesController extends Controller
{
    public function index(): View
    {
        $valores = [];
        foreach (config('redes.redes') as $red) {
            $valores[$red['clave']] = ConfiguracionWeb::obtener('red_'.$red['clave']);
        }

        return view('panel.redes.index', [
            'redes' => config('redes.redes'),
            'valores' => $valores,
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $request->validate([
            'redes' => ['nullable', 'array'],
            'redes.*' => ['nullable', 'url', 'max:255'],
        ]);

        foreach (config('redes.redes') as $red) {
            ConfiguracionWeb::guardar('red_'.$red['clave'], trim((string) $request->input('redes.'.$red['clave'], '')));
        }

        return back()->with('exito', 'Redes sociales actualizadas.');
    }
}
