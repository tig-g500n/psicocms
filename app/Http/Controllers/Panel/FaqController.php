<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('panel.faq.index', [
            'faqs' => Faq::orderBy('orden')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('panel.faq.form', [
            'faq' => new Faq(['activo' => true, 'orden' => (Faq::max('orden') ?? 0) + 1]),
            'modo' => 'crear',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validar($request));

        return redirect()->route('panel.faq.index')->with('exito', 'Pregunta creada correctamente.');
    }

    public function edit(Faq $faq): View
    {
        return view('panel.faq.form', [
            'faq' => $faq,
            'modo' => 'editar',
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validar($request));

        return redirect()->route('panel.faq.index')->with('exito', 'Pregunta actualizada correctamente.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('exito', 'Pregunta eliminada correctamente.');
    }

    public function toggle(Faq $faq): RedirectResponse
    {
        $faq->update(['activo' => ! $faq->activo]);

        return back()->with('exito', 'Estado de la pregunta actualizado.');
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'pregunta' => ['required', 'string', 'max:255'],
            'respuesta' => ['nullable', 'string'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');
        $datos['orden'] = $datos['orden'] ?? 0;

        return $datos;
    }
}
