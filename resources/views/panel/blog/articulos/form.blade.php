@extends('panel.layouts.app')

@section('titulo', $modo === 'crear' ? 'Nuevo artículo' : 'Editar artículo')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/blog.css') }}" rel="stylesheet">
@endpush

@php
    $accion = $modo === 'crear' ? route('panel.blog.articulos.store') : route('panel.blog.articulos.update', $articulo);
@endphp

@section('contenido')
    <x-panel.page-header :titulo="$modo === 'crear' ? 'Nuevo artículo' : 'Editar artículo'" subtitulo="Redacta el contenido y publícalo cuando esté listo.">
        <x-slot:acciones>
            <a href="{{ route('panel.blog.articulos.index') }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <form method="POST" action="{{ $accion }}" enctype="multipart/form-data">
        @csrf
        @if ($modo === 'editar')
            @method('PUT')
        @endif

        <div class="blog-form">
            <div class="card">
                <div class="campo">
                    <label class="campo__label" for="titulo">Título</label>
                    <input class="campo__input" type="text" id="titulo" name="titulo" value="{{ old('titulo', $articulo->titulo) }}" required>
                </div>

                <div class="campo">
                    <label class="campo__label" for="extracto">Extracto (resumen breve)</label>
                    <textarea class="campo__input" id="extracto" name="extracto" rows="2" placeholder="Frase corta que resume el artículo (opcional).">{{ old('extracto', $articulo->extracto) }}</textarea>
                </div>

                <div class="campo">
                    <label class="campo__label" for="contenido">Contenido</label>
                    <x-panel.wysiwyg name="contenido" :value="old('contenido', $articulo->contenido)" />
                </div>
            </div>

            <div class="blog-form__lado">
                <div class="card">
                    <h2 class="card__title" style="margin-bottom:1.6rem;">Publicación</h2>

                    <div class="campo">
                        <label class="campo__label">Estado</label>
                        <x-panel.toggle name="publicado" :checked="old('publicado', $articulo->publicado)" label="Publicado" />
                    </div>

                    <div class="campo">
                        <label class="campo__label" for="categoria_id">Categoría</label>
                        <select class="campo__input" id="categoria_id" name="categoria_id">
                            <option value="">Sin categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $articulo->categoria_id) == $categoria->id)>{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="campo">
                        <label class="campo__label" for="fecha">Fecha</label>
                        <input class="campo__input" type="date" id="fecha" name="fecha" value="{{ old('fecha', optional($articulo->fecha)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="card">
                    <h2 class="card__title" style="margin-bottom:1.6rem;">Imagen destacada</h2>
                    <label class="blog-uploader" for="imagen">
                        <span class="blog-uploader__preview" data-img-preview="#blog-preview" id="blog-preview">
                            @if ($articulo->imagen)
                                <img src="{{ asset($articulo->imagen) }}" alt="Imagen actual">
                            @else
                                <i class="fa-solid fa-image"></i>
                            @endif
                        </span>
                        <span class="blog-uploader__text">
                            <strong>Subir imagen</strong>
                            <span class="campo__hint">JPG, PNG o WEBP, máx. 4 MB.</span>
                        </span>
                        <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp" hidden data-img-input="#blog-preview">
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:1rem;margin-top:2.4rem;">
            <a href="{{ route('panel.blog.articulos.index') }}" class="btn btn--ghost">Cancelar</a>
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar artículo</button>
        </div>
    </form>
@endsection
