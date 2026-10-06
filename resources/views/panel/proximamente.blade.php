@extends('panel.layouts.app')

@php
    $titulos = [
        'citas' => 'Citas',
        'calendario' => 'Calendario',
        'pacientes' => 'Pacientes',
        'historias' => 'Historias clínicas',
        'blog' => 'Blog',
        'servicios' => 'Servicios',
        'especialidades' => 'Especialidades',
        'faq' => 'Preguntas frecuentes',
        'frases' => 'Frases públicas',
        'redes' => 'Redes sociales',
        'imagenes' => 'Imágenes',
        'info-publica' => 'Información pública',
        'disponibilidad' => 'Disponibilidad',
        'temas' => 'Temas visuales',
        'notificaciones' => 'Email y notificaciones',
        'perfil' => 'Mi perfil',
        'apariencia' => 'Apariencia del panel',
        'ayuda' => 'Centro de ayuda',
        'buscador' => 'Búsqueda',
    ];
    $titulo = $titulos[$seccion] ?? 'Sección';
@endphp

@section('titulo', $titulo)

@section('contenido')
    <x-panel.page-header :titulo="$titulo" subtitulo="Esta sección estará disponible próximamente." />

    <div class="card">
        <x-panel.empty-state
            icono="fa-screwdriver-wrench"
            titulo="En construcción"
            :texto="'La sección «'.$titulo.'» se desarrollará en una fase próxima del proyecto. El menú y el diseño del panel ya están listos.'">
            <x-slot:accion>
                <a href="{{ route('panel.inicio') }}" class="btn btn--soft"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
            </x-slot:accion>
        </x-panel.empty-state>
    </div>
@endsection
