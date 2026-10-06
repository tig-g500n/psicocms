@extends('publico.layouts.sitio')

@section('titulo', ($categoriaActiva ? $categoriaActiva->nombre.' · ' : '').'Blog · '.$nombreCompleto)

@section('contenido')
    @include('publico.secciones.pagehead', ['eyebrow' => 'Recursos', 'titulo' => 'Blog'])

    <section class="pp-seccion">
        <div class="pp-wrap">
            @if ($categorias->isNotEmpty())
                <div class="pp-filtros">
                    <a href="{{ route('publico.blog') }}" class="pp-filtro {{ ! $categoriaActiva ? 'is-active' : '' }}">Todas</a>
                    @foreach ($categorias as $categoria)
                        <a href="{{ route('publico.blog', ['categoria' => $categoria->slug]) }}"
                           class="pp-filtro {{ $categoriaActiva && $categoriaActiva->id === $categoria->id ? 'is-active' : '' }}">
                            {{ $categoria->nombre }} <span>{{ $categoria->articulos_count }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($articulos->isNotEmpty())
                <div class="pp-cards pp-cards--blog">
                    @foreach ($articulos as $articulo)
                        <a href="{{ route('publico.articulo', $articulo->slug) }}" class="pp-articulo">
                            <span class="pp-articulo__img">
                                @if ($articulo->imagen)
                                    <img src="{{ asset($articulo->imagen) }}" alt="{{ $articulo->titulo }}" loading="lazy">
                                @else
                                    <i class="fa-solid fa-newspaper"></i>
                                @endif
                            </span>
                            <span class="pp-articulo__cuerpo">
                                @if ($articulo->categoria)
                                    <span class="pp-articulo__cat">{{ $articulo->categoria->nombre }}</span>
                                @endif
                                <span class="pp-articulo__titulo">{{ $articulo->titulo }}</span>
                                @if ($articulo->extracto)
                                    <span class="pp-articulo__extracto">{{ \Illuminate\Support\Str::limit($articulo->extracto, 120) }}</span>
                                @endif
                                <span class="pp-articulo__fecha">{{ optional($articulo->fecha)->format('d/m/Y') }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>

                @if ($articulos->hasPages())
                    <nav class="pp-paginacion" aria-label="Paginación del blog">
                        @if ($articulos->onFirstPage())
                            <span class="pp-paginacion__btn is-off"><i class="fa-solid fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $articulos->previousPageUrl() }}" class="pp-paginacion__btn"><i class="fa-solid fa-chevron-left"></i></a>
                        @endif

                        <span class="pp-paginacion__info">Página {{ $articulos->currentPage() }} de {{ $articulos->lastPage() }}</span>

                        @if ($articulos->hasMorePages())
                            <a href="{{ $articulos->nextPageUrl() }}" class="pp-paginacion__btn"><i class="fa-solid fa-chevron-right"></i></a>
                        @else
                            <span class="pp-paginacion__btn is-off"><i class="fa-solid fa-chevron-right"></i></span>
                        @endif
                    </nav>
                @endif
            @else
                <p class="pp-vacio">
                    @if ($categoriaActiva)
                        No hay artículos en «{{ $categoriaActiva->nombre }}» todavía.
                    @else
                        Todavía no hay artículos publicados. ¡Vuelve pronto!
                    @endif
                </p>
            @endif
        </div>
    </section>
@endsection
