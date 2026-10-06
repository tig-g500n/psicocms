@extends('panel.layouts.app')

@section('titulo', $paciente->nombre)

@push('estilos')
<link href="{{ asset('assets/dashboard/css/pacientes.css') }}" rel="stylesheet">
@endpush

@php
    $badgeEstado = ['activo' => 'green', 'pausado' => 'amber', 'inactivo' => 'danger'];
    $badgeCita = ['pendiente' => 'amber', 'confirmada' => 'green', 'realizada' => 'info', 'cancelada' => 'danger'];
@endphp

@section('contenido')
    <x-panel.page-header :titulo="$paciente->nombre" :subtitulo="'Paciente · '.$paciente->telefono">
        <x-slot:acciones>
            <a href="{{ route('panel.pacientes.index') }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
            <a href="{{ route('panel.pacientes.edit', $paciente) }}" class="btn btn--ghost"><i class="fa-solid fa-pen"></i> Editar</a>
            <a href="{{ route('panel.pacientes.proteccion-pdf', $paciente) }}" class="btn btn--ghost"><i class="fa-solid fa-file-shield"></i> PDF protección de datos</a>
            <a href="{{ route('panel.citas.create', ['telefono' => $paciente->telefono, 'modalidad' => $paciente->modalidad_pref]) }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva cita</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="pac-detalle">
        <div class="pac-detalle__lado">
            <div class="card">
                <div class="pac-detalle__cabecera">
                    <span class="pac-avatar pac-avatar--lg">{{ mb_strtoupper(mb_substr($paciente->nombre, 0, 1)) }}</span>
                    <div>
                        <h2 class="pac-detalle__nombre">{{ $paciente->nombre }}</h2>
                        <x-panel.badge :color="$badgeEstado[$paciente->estado] ?? ''">{{ ucfirst($paciente->estado) }}</x-panel.badge>
                    </div>
                </div>

                <dl class="pac-datos">
                    <div><dt>Teléfono</dt><dd>{{ $paciente->telefono }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $paciente->email ?: '—' }}</dd></div>
                    <div><dt>Nacimiento</dt><dd>{{ $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('d/m/Y').' ('.$paciente->fecha_nacimiento->age.' años)' : '—' }}</dd></div>
                    <div><dt>Género</dt><dd>{{ $paciente->genero ?: '—' }}</dd></div>
                    <div><dt>Modalidad</dt><dd>{{ $paciente->modalidad_pref ? ucfirst($paciente->modalidad_pref) : '—' }}</dd></div>
                    <div><dt>Dirección</dt><dd>{{ $paciente->direccion ?: '—' }}</dd></div>
                </dl>

                @if ($paciente->motivo)
                    <div class="pac-bloque">
                        <h3 class="pac-bloque__titulo">Motivo de consulta</h3>
                        <p>{{ $paciente->motivo }}</p>
                    </div>
                @endif
                @if ($paciente->notas)
                    <div class="pac-bloque">
                        <h3 class="pac-bloque__titulo">Notas internas</h3>
                        <p>{{ $paciente->notas }}</p>
                    </div>
                @endif
            </div>

            <div class="card">
                <h3 class="card__title" style="margin-bottom:1rem;">Historia clínica</h3>
                <p class="campo__hint" style="margin-bottom:1.4rem;">Seguimiento de sesiones, anotaciones y documentos del paciente.</p>
                <a href="{{ route('panel.pacientes.historia', $paciente) }}" class="btn btn--primary" style="width:100%;">
                    <i class="fa-solid fa-notes-medical"></i> Ver historia clínica
                </a>
            </div>
        </div>

        <div class="pac-detalle__main">
            <x-panel.card titulo="Citas del paciente">
                @if ($citas->isEmpty())
                    <x-panel.empty-state icono="fa-calendar-xmark" titulo="Sin citas" texto="Este paciente todavía no tiene citas registradas.">
                        <x-slot:accion>
                            <a href="{{ route('panel.citas.create', ['telefono' => $paciente->telefono]) }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva cita</a>
                        </x-slot:accion>
                    </x-panel.empty-state>
                @else
                    <div class="tabla-wrap" style="border:none;box-shadow:none;">
                        <table class="tabla">
                            <thead><tr><th>Fecha</th><th>Hora</th><th>Modalidad</th><th>Estado</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($citas as $cita)
                                    <tr>
                                        <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                        <td>{{ $cita->hora_inicio_corta }} - {{ $cita->hora_fin_corta }}</td>
                                        <td><x-panel.badge :color="$cita->modalidad === 'online' ? 'info' : 'green'">{{ ucfirst($cita->modalidad) }}</x-panel.badge></td>
                                        <td><x-panel.badge :color="$badgeCita[$cita->estado] ?? ''">{{ ucfirst($cita->estado) }}</x-panel.badge></td>
                                        <td style="text-align:right;"><a href="{{ route('panel.citas.edit', $cita) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($citas->hasPages())
                        <div class="paginacion">
                            @if (! $citas->onFirstPage())<a href="{{ $citas->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>@endif
                            @foreach ($citas->getUrlRange(1, $citas->lastPage()) as $pagina => $url)
                                <a href="{{ $url }}" class="{{ $pagina == $citas->currentPage() ? 'is-active' : '' }}">{{ $pagina }}</a>
                            @endforeach
                            @if ($citas->hasMorePages())<a href="{{ $citas->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>@endif
                        </div>
                    @endif
                @endif
            </x-panel.card>
        </div>
    </div>
@endsection
