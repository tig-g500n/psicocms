<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\FrasePublica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FraseController extends Controller
{
    public function index(): View
    {
        return view('panel.frases.index', [
            'grupos' => FrasePublica::catalogo(),
            'overrides' => FrasePublica::overrides(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $request->validate([
            'frases' => ['nullable', 'array'],
            'frases.*' => ['nullable', 'string', 'max:2000'],
        ]);

        $frases = $request->input('frases', []);

        foreach (FrasePublica::items() as $clave => $item) {
            $texto = trim((string) ($frases[$clave] ?? ''));

            if ($texto === '' || $texto === $item['defecto']) {
                FrasePublica::where('clave', $clave)->delete();
            } else {
                FrasePublica::updateOrCreate(['clave' => $clave], ['texto' => $texto]);
            }
        }

        return back()->with('exito', 'Frases públicas actualizadas.');
    }
}
