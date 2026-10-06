@extends('panel.layouts.app')

@section('titulo', 'Categorías del blog')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/blog.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Categorías del blog" subtitulo="Organiza tus artículos por temática.">
        <x-slot:acciones>
            <a href="{{ route('panel.blog.articulos.index') }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver al blog</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="blog-cats">
        <div class="card">
            <h2 class="card__title" style="margin-bottom:1.6rem;">Nueva categoría</h2>
            <form method="POST" action="{{ route('panel.blog.categorias.store') }}">
                @csrf
                <div class="campo">
                    <label class="campo__label" for="nombre">Nombre</label>
                    <input class="campo__input" type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Autoestima" required>
                </div>
                <button type="submit" class="btn btn--primary" style="width:100%;"><i class="fa-solid fa-plus"></i> Añadir categoría</button>
            </form>
        </div>

        <div class="card">
            <h2 class="card__title" style="margin-bottom:1.6rem;">Categorías existentes</h2>
            @if ($categorias->isEmpty())
                <x-panel.empty-state icono="fa-tags" titulo="Sin categorías" texto="Crea la primera categoría con el formulario de la izquierda." />
            @else
                <div class="blog-cats__lista">
                    @foreach ($categorias as $categoria)
                        <div class="blog-cat">
                            <form method="POST" action="{{ route('panel.blog.categorias.update', $categoria) }}" class="blog-cat__form">
                                @csrf
                                @method('PUT')
                                <input class="campo__input" type="text" name="nombre" value="{{ $categoria->nombre }}" required>
                                <button type="submit" class="btn btn--soft btn--sm" aria-label="Guardar"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <span class="blog-cat__count">{{ $categoria->articulos_count }} art.</span>
                            <form method="POST" action="{{ route('panel.blog.categorias.destroy', $categoria) }}" data-confirm="¿Eliminar la categoría «{{ $categoria->nombre }}»? Los artículos que la usen quedarán sin categoría.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
