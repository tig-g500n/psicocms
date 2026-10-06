@extends('publico.layouts.sitio')

@section('titulo', 'Preguntas frecuentes · '.$nombreCompleto)

@section('contenido')
    @include('publico.secciones.pagehead', ['eyebrow' => 'Dudas habituales', 'titulo' => 'Preguntas frecuentes'])
    @include('publico.secciones.faq')
@endsection
