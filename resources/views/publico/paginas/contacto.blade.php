@extends('publico.layouts.sitio')

@section('titulo', 'Pide cita · '.$nombreCompleto)

@section('contenido')
    @include('publico.secciones.pagehead', ['eyebrow' => 'Reservas y contacto', 'titulo' => 'Pide tu cita'])
    @include('publico.secciones.contacto')
@endsection
