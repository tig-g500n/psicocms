@extends('panel.layouts.app')

@section('titulo', 'Calendario')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/calendario.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <div class="cal"
         data-calendario
         data-eventos-url="{{ route('panel.calendario.eventos') }}"
         data-crear-url="{{ route('panel.citas.create') }}">

        <div class="cal__main">
            <div class="cal__toolbar">
                <div class="cal__nav">
                    <h1 class="cal__titulo" data-cal-titulo>Cargando…</h1>
                    <div class="cal__nav-btns">
                        <button type="button" class="cal__nav-btn" data-cal-prev aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
                        <button type="button" class="cal__hoy" data-cal-hoy>Hoy</button>
                        <button type="button" class="cal__nav-btn" data-cal-next aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="cal__vistas" role="tablist">
                    <button type="button" class="cal__vista is-active" data-cal-vista="mes">Mes</button>
                    <button type="button" class="cal__vista" data-cal-vista="semana">Semana</button>
                    <button type="button" class="cal__vista" data-cal-vista="dia">Día</button>
                </div>
            </div>

            <div class="cal__cuerpo" data-cal-cuerpo>
                <div class="cal__cargando"><i class="fa-solid fa-spinner fa-spin"></i> Cargando calendario…</div>
            </div>
        </div>

        <aside class="cal__lado">
            <a href="{{ route('panel.citas.create') }}" class="btn btn--primary cal__nueva" data-cal-nueva>
                <i class="fa-solid fa-circle-plus"></i> Nueva cita
            </a>

            <div class="card cal__agenda">
                <h2 class="cal__agenda-titulo" data-cal-agenda-titulo>Agenda del día</h2>
                <p class="cal__agenda-fecha" data-cal-agenda-fecha></p>
                <div class="cal__agenda-lista" data-cal-agenda-lista></div>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/dashboard/js/calendario.js') }}"></script>
@endpush
