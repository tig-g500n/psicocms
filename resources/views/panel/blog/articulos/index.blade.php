@extends('panel.layouts.app')

@section('titulo', 'Blog')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/blog.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Blog" subtitulo="Gestiona los artículos de tu web.">
        <x-slot:acciones>
            <a href="{{ route('panel.blog.categorias.index') }}" class="btn btn--ghost"><i class="fa-solid fa-tags"></i> Categorías</a>
            <a href="{{ route('panel.blog.articulos.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nuevo artículo</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="card" style="margin-bottom:2.4rem;">
        <form method="GET" action="{{ route('panel.blog.articulos.index') }}" class="citas-filtros">
            <div class="citas-filtros__campo">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar por título">
            </div>
            <select name="categoria" class="campo__input">
                <option value="">Categoría: todas</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(($filtros['categoria'] ?? '') == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            <select name="estado" class="campo__input">
                <option value="">Estado: todos</option>
                <option value="publicado" @selected(($filtros['estado'] ?? '') === 'publicado')>Publicados</option>
                <option value="borrador" @selected(($filtros['estado'] ?? '') === 'borrador')>Borradores</option>
            </select>
            <button type="submit" class="btn btn--soft">Filtrar</button>
            @if (array_filter($filtros))
                <a href="{{ route('panel.blog.articulos.index') }}" class="btn btn--ghost">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if ($articulos->isEmpty())
            <x-panel.empty-state icono="fa-newspaper" titulo="Aún no hay artículos" texto="Crea tu primer artículo para tu blog y compártelo con tus pacientes.">
                <x-slot:accion>
                    <a href="{{ route('panel.blog.articulos.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nuevo artículo</a>
                </x-slot:accion>
            </x-panel.empty-state>
        @else
            <div class="tabla-wrap" style="border:none;box-shadow:none;">
                <table class="tabla">
                    <thead>
                        <tr><th>Artículo</th><th>Categoría</th><th>Fecha</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $articulo)
                            <tr>
                                <td>
                                    <div class="blog-fila">
                                        <div class="blog-fila__img">
                                            @if ($articulo->imagen)
                                                <img src="{{ asset($articulo->imagen) }}" alt="">
                                            @else
                                                <i class="fa-solid fa-image"></i>
                                            @endif
                                        </div>
                                        <strong>{{ $articulo->titulo }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if ($articulo->categoria)
                                        <x-panel.badge>{{ $articulo->categoria->nombre }}</x-panel.badge>
                                    @else
                                        <span class="campo__hint">Sin categoría</span>
                                    @endif
                                </td>
                                <td>{{ optional($articulo->fecha)->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <x-panel.badge :color="$articulo->publicado ? 'green' : 'amber'">
                                        {{ $articulo->publicado ? 'Publicado' : 'Borrador' }}
                                    </x-panel.badge>
                                </td>
                                <td>
                                    <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                                        <a href="{{ route('panel.blog.articulos.edit', $articulo) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a>
                                        <form method="POST" action="{{ route('panel.blog.articulos.destroy', $articulo) }}" data-confirm="¿Eliminar el artículo «{{ $articulo->titulo }}»? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($articulos->hasPages())
                <div class="paginacion">
                    @if ($articulos->onFirstPage())
                        <span class="is-disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $articulos->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                    @endif
                    @foreach ($articulos->getUrlRange(1, $articulos->lastPage()) as $pagina => $url)
                        <a href="{{ $url }}" class="{{ $pagina == $articulos->currentPage() ? 'is-active' : '' }}">{{ $pagina }}</a>
                    @endforeach
                    @if ($articulos->hasMorePages())
                        <a href="{{ $articulos->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <span class="is-disabled"><i class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
