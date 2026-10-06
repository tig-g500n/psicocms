@extends('publico.layouts.sitio')

@section('titulo', 'Servicios · '.$nombreCompleto)

@section('contenido')
    @include('publico.secciones.pagehead', ['eyebrow' => 'Qué ofrezco', 'titulo' => 'Servicios'])
    @include('publico.secciones.servicios')
    @include('publico.secciones.planes')
@endsection
