@extends('publico.layouts.sitio')

@section('contenido')
    @include('publico.secciones.hero')
    @include('publico.secciones.sobre-mi')
    @include('publico.secciones.servicios')
    @include('publico.secciones.especialidades')
    @include('publico.secciones.planes')
    @include('publico.secciones.blog')
    @include('publico.secciones.faq')
    @include('publico.secciones.contacto')
@endsection
