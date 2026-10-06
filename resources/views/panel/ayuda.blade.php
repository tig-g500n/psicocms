@extends('panel.layouts.app')

@section('titulo', 'Ayuda')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@php
    $secciones = [
        ['icono' => 'fa-gauge-high', 'titulo' => 'Inicio', 'texto' => 'Es tu pantalla de resumen: verás las citas de hoy, tus pacientes activos y accesos rápidos. Empieza siempre aquí cada día.'],
        ['icono' => 'fa-calendar-check', 'titulo' => 'Citas', 'texto' => 'Consulta, crea, edita o elimina citas. Puedes filtrar por fecha, modalidad y estado. Al crear una cita, el sistema solo te deja elegir horarios libres según tu disponibilidad.'],
        ['icono' => 'fa-calendar-days', 'titulo' => 'Calendario', 'texto' => 'Vista tipo agenda (mes, semana o día) con todas tus citas. Haz clic en un día para ver su agenda o en una cita para editarla.'],
        ['icono' => 'fa-user-group', 'titulo' => 'Pacientes', 'texto' => 'Ficha de cada paciente. Se crean solos cuando alguien reserva desde tu web, pero también puedes añadirlos a mano. El teléfono es su identificador único.'],
        ['icono' => 'fa-notes-medical', 'titulo' => 'Historias', 'texto' => 'Dentro de cada paciente puedes escribir el seguimiento de cada sesión y adjuntar fotos o PDFs. Es privado y seguro.'],
        ['icono' => 'fa-clock', 'titulo' => 'Disponibilidad', 'texto' => 'Configura tus horarios online y presenciales, la duración de las sesiones y los descansos. También el «modo vacaciones» y los periodos de vacaciones para bloquear fechas.'],
        ['icono' => 'fa-newspaper', 'titulo' => 'Blog', 'texto' => 'Escribe artículos con imágenes y categorías. Puedes guardarlos como borrador y publicarlos cuando quieras.'],
        ['icono' => 'fa-palette', 'titulo' => 'Temas e imágenes', 'texto' => 'Cambia el aspecto de tu web pública eligiendo entre varios temas, y sustituye las imágenes de ejemplo por las tuyas.'],
        ['icono' => 'fa-toggle-on', 'titulo' => 'Secciones y frases', 'texto' => 'Activa o desactiva secciones de tu web (blog, reservas, etc.) y personaliza los textos que aparecen en ella.'],
        ['icono' => 'fa-envelope', 'titulo' => 'Notificaciones', 'texto' => 'Configura tu Gmail para recibir un aviso por email cada vez que alguien reserve una cita.'],
        ['icono' => 'fa-circle-half-stroke', 'titulo' => 'Apariencia del panel', 'texto' => 'Desde tu avatar (arriba a la derecha) puedes elegir el modo claro u oscuro y el color principal del panel.'],
    ];
@endphp

@section('contenido')
    <x-panel.page-header titulo="Centro de ayuda"
        subtitulo="Una guía rápida y sencilla de todo lo que puedes hacer en tu panel de PsicoCMS.">
    </x-panel.page-header>

    <x-panel.card class="ayuda-intro">
        <p>Bienvenida a <strong>PsicoCMS</strong>. Este panel te permite gestionar toda tu consulta y tu web pública desde un mismo sitio. A la izquierda tienes el menú: los accesos rápidos arriba y las configuraciones agrupadas debajo. Aquí tienes qué hace cada sección.</p>
    </x-panel.card>

    <div class="ayuda-grid">
        @foreach ($secciones as $s)
            <article class="ayuda-item">
                <div class="ayuda-item__icono"><i class="fa-solid {{ $s['icono'] }}"></i></div>
                <div>
                    <h3 class="ayuda-item__titulo">{{ $s['titulo'] }}</h3>
                    <p class="ayuda-item__texto">{{ $s['texto'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
@endsection
