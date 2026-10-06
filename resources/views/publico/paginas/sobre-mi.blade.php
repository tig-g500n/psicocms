@extends('publico.layouts.sitio')

@section('titulo', 'Sobre mí · '.$nombreCompleto)

@section('contenido')
    @include('publico.secciones.pagehead', ['eyebrow' => 'Conóceme', 'titulo' => 'Sobre mí'])
    @include('publico.secciones.sobre-mi')
    @include('publico.secciones.especialidades')
@endsection
