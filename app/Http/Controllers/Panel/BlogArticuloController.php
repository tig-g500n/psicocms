<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlogArticulo;
use App\Models\BlogCategoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogArticuloController extends Controller
{
    public function index(Request $request): View
    {
        $articulos = BlogArticulo::with('categoria')
            ->when($request->filled('q'), fn ($q) => $q->where('titulo', 'like', '%'.$request->q.'%'))
            ->when($request->filled('categoria'), fn ($q) => $q->where('categoria_id', $request->categoria))
            ->when($request->filled('estado'), fn ($q) => $q->where('publicado', $request->estado === 'publicado'))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('panel.blog.articulos.index', [
            'articulos' => $articulos,
            'categorias' => BlogCategoria::orderBy('nombre')->get(),
            'filtros' => $request->only(['q', 'categoria', 'estado']),
        ]);
    }

    public function create(): View
    {
        return view('panel.blog.articulos.form', [
            'articulo' => new BlogArticulo(['publicado' => false, 'fecha' => now()]),
            'categorias' => BlogCategoria::orderBy('nombre')->get(),
            'modo' => 'crear',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['publicado'] = $request->boolean('publicado');
        $datos['imagen'] = $this->guardarImagen($request);

        BlogArticulo::create($datos);

        return redirect()->route('panel.blog.articulos.index')->with('exito', 'Artículo creado correctamente.');
    }

    public function edit(BlogArticulo $articulo): View
    {
        return view('panel.blog.articulos.form', [
            'articulo' => $articulo,
            'categorias' => BlogCategoria::orderBy('nombre')->get(),
            'modo' => 'editar',
        ]);
    }

    public function update(Request $request, BlogArticulo $articulo): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['publicado'] = $request->boolean('publicado');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $this->guardarImagen($request);
        }

        $articulo->update($datos);

        return redirect()->route('panel.blog.articulos.index')->with('exito', 'Artículo actualizado correctamente.');
    }

    public function destroy(BlogArticulo $articulo): RedirectResponse
    {
        $articulo->delete();

        return back()->with('exito', 'Artículo eliminado correctamente.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria_id' => ['nullable', 'exists:blog_categorias,id'],
            'extracto' => ['nullable', 'string', 'max:500'],
            'contenido' => ['nullable', 'string'],
            'fecha' => ['nullable', 'date'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function guardarImagen(Request $request): ?string
    {
        if (! $request->hasFile('imagen')) {
            return null;
        }

        $nombre = 'blog_'.uniqid().'.'.$request->file('imagen')->getClientOriginalExtension();
        $request->file('imagen')->move(public_path('uploads/blog'), $nombre);

        return 'uploads/blog/'.$nombre;
    }
}
