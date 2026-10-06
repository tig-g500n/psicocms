<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlogCategoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoriaController extends Controller
{
    public function index(): View
    {
        return view('panel.blog.categorias.index', [
            'categorias' => BlogCategoria::withCount('articulos')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        BlogCategoria::create($datos);

        return back()->with('exito', 'Categoría creada correctamente.');
    }

    public function update(Request $request, BlogCategoria $categoria): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $categoria->update([
            'nombre' => $datos['nombre'],
            'slug' => BlogCategoria::slugUnico($datos['nombre'], $categoria->id),
        ]);

        return back()->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(BlogCategoria $categoria): RedirectResponse
    {
        $categoria->delete();

        return back()->with('exito', 'Categoría eliminada correctamente.');
    }
}
