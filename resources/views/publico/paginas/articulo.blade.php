@extends('publico.layouts.sitio')

@section('titulo', $articulo->titulo.' · '.$nombreCompleto)
@section('og_type', 'article')
@section('meta_descripcion', \Illuminate\Support\Str::limit(strip_tags($articulo->extracto ?: $articulo->contenido), 160))

@push('meta')
    @if ($articulo->fecha)
        <meta property="article:published_time" content="{{ $articulo->fecha->toIso8601String() }}">
    @endif
    @if ($articulo->categoria)
        <meta property="article:section" content="{{ $articulo->categoria->nombre }}">
    @endif
    @if ($articulo->imagen)
        <meta property="og:image" content="{{ asset($articulo->imagen) }}">
    @endif
@endpush

@section('contenido')
    @include('publico.secciones.pagehead', [
        'eyebrow' => $articulo->categoria->nombre ?? 'Blog',
        'titulo' => $articulo->titulo,
    ])

    <article class="pp-seccion">
        <div class="pp-wrap pp-wrap--estrecho">
            <p class="pp-post__meta">
                <i class="fa-regular fa-calendar"></i> {{ optional($articulo->fecha)->format('d/m/Y') }}
            </p>

            @if ($articulo->imagen)
                <img src="{{ asset($articulo->imagen) }}" alt="{{ $articulo->titulo }}" class="pp-post__imagen">
            @endif

            <div class="pp-richtext pp-post__cuerpo">{!! $articulo->contenido !!}</div>

            <div class="pp-post__volver">
                <a href="{{ route('publico.blog') }}" class="pp-btn pp-btn--outline"><i class="fa-solid fa-arrow-left"></i> Volver al blog</a>
            </div>
        </div>
    </article>
@endsection
