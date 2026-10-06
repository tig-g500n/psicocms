@extends('panel.layouts.app')

@section('titulo', 'Disponibilidad')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/disponibilidad.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header
        titulo="Configuración de disponibilidad"
        subtitulo="Define tus horarios de consulta online y presenciales. Estos bloques determinan cuándo pueden reservar tus pacientes." />

    <div class="disp-top">
        <div class="card">
            <div class="disp-vac-head">
                <span class="disp-vac-icon"><i class="fa-solid fa-plane-departure"></i></span>
                <div>
                    <h2 class="card__title">Modo vacaciones</h2>
                    <p class="campo__hint">Pausa temporalmente todas las nuevas reservas. Las citas existentes no se cancelan.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('panel.disponibilidad.modo-vacaciones') }}" data-autosubmit>
                @csrf
                <label class="toggle">
                    <input type="checkbox" name="modo_vacaciones" value="1" {{ $modoVacaciones ? 'checked' : '' }} onchange="this.form.requestSubmit()">
                    <span class="toggle__track"></span>
                    <span class="toggle__label">{{ $modoVacaciones ? 'Activado' : 'Desactivado' }}</span>
                </label>
            </form>
        </div>

        <div class="card">
            <h2 class="card__title" style="margin-bottom:1.6rem;">Periodos de vacaciones</h2>
            <form method="POST" action="{{ route('panel.vacaciones.agregar') }}" class="disp-vac-form">
                @csrf
                <div class="campo" style="margin:0;">
                    <label class="campo__label" for="vac_inicio">Desde</label>
                    <input class="campo__input" type="date" id="vac_inicio" name="fecha_inicio" required>
                </div>
                <div class="campo" style="margin:0;">
                    <label class="campo__label" for="vac_fin">Hasta</label>
                    <input class="campo__input" type="date" id="vac_fin" name="fecha_fin" required>
                </div>
                <button type="submit" class="btn btn--soft"><i class="fa-solid fa-plus"></i> Añadir</button>
            </form>

            <div class="disp-vac-list">
                @forelse ($periodos as $periodo)
                    <div class="disp-vac-item">
                        <span><i class="fa-solid fa-calendar-xmark"></i> {{ $periodo->fecha_inicio->format('d/m/Y') }} — {{ $periodo->fecha_fin->format('d/m/Y') }}</span>
                        <form method="POST" action="{{ route('panel.vacaciones.eliminar', $periodo) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                @empty
                    <p class="campo__hint" style="padding:1rem 0;">No hay periodos de vacaciones configurados.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:2.4rem;">
        <div class="disp-tabs" role="tablist">
            <button type="button" class="disp-tab is-active" data-tab="online"><i class="fa-solid fa-globe"></i> Online</button>
            <button type="button" class="disp-tab" data-tab="presencial"><i class="fa-solid fa-location-dot"></i> Presencial</button>
        </div>

        @include('panel.disponibilidad._modalidad', ['modalidad' => 'online', 'config' => $configOnline, 'grid' => $gridOnline])
        @include('panel.disponibilidad._modalidad', ['modalidad' => 'presencial', 'config' => $configPresencial, 'grid' => $gridPresencial])
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/dashboard/js/disponibilidad.js') }}"></script>
@endpush
