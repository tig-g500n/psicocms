@extends('panel.layouts.app')

@section('titulo', 'Citas')

@php
    $badgeEstado = ['pendiente' => 'amber', 'confirmada' => 'green', 'realizada' => 'info', 'cancelada' => 'danger'];
@endphp

@section('contenido')
    <x-panel.page-header titulo="Gestión de citas" subtitulo="Consulta, filtra y administra todas tus citas.">
        <x-slot:acciones>
            <a href="{{ route('panel.citas.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva cita</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="card" style="margin-bottom:2.4rem;">
        <form method="GET" action="{{ route('panel.citas.index') }}" class="citas-filtros">
            <div class="citas-filtros__campo">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar por paciente o teléfono">
            </div>
            <select name="modalidad" class="campo__input">
                <option value="">Modalidad: todas</option>
                <option value="online" @selected(($filtros['modalidad'] ?? '') === 'online')>Online</option>
                <option value="presencial" @selected(($filtros['modalidad'] ?? '') === 'presencial')>Presencial</option>
            </select>
            <select name="estado" class="campo__input">
                <option value="">Estado: todos</option>
                @foreach (['pendiente', 'confirmada', 'realizada', 'cancelada'] as $estado)
                    <option value="{{ $estado }}" @selected(($filtros['estado'] ?? '') === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
            <input type="date" name="fecha" value="{{ $filtros['fecha'] ?? '' }}" class="campo__input">
            <button type="submit" class="btn btn--soft">Filtrar</button>
            @if (array_filter($filtros))
                <a href="{{ route('panel.citas.index') }}" class="btn btn--ghost">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if ($citas->isEmpty())
            <x-panel.empty-state icono="fa-calendar-plus" titulo="No hay citas" texto="No se encontraron citas con estos filtros. Crea una nueva cita para empezar.">
                <x-slot:accion>
                    <a href="{{ route('panel.citas.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva cita</a>
                </x-slot:accion>
            </x-panel.empty-state>
        @else
            <div class="tabla-wrap" style="border:none;box-shadow:none;">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th><th>Hora</th><th>Paciente</th><th>Modalidad</th><th>Estado</th><th style="text-align:right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $cita)
                            <tr>
                                <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                <td>{{ $cita->hora_inicio_corta }} - {{ $cita->hora_fin_corta }}</td>
                                <td>
                                    <strong>{{ $cita->paciente->nombre ?? '—' }}</strong><br>
                                    <span class="campo__hint">{{ $cita->paciente->telefono ?? '' }}</span>
                                </td>
                                <td><x-panel.badge :color="$cita->modalidad === 'online' ? 'info' : 'green'">{{ ucfirst($cita->modalidad) }}</x-panel.badge></td>
                                <td><x-panel.badge :color="$badgeEstado[$cita->estado] ?? ''">{{ ucfirst($cita->estado) }}</x-panel.badge></td>
                                <td>
                                    <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                                        <a href="{{ route('panel.citas.edit', $cita) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a>
                                        <form method="POST" action="{{ route('panel.citas.destroy', $cita) }}" data-confirm="¿Eliminar esta cita de {{ $cita->paciente->nombre ?? 'paciente' }}? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($citas->hasPages())
                <div class="paginacion">
                    @if ($citas->onFirstPage())
                        <span class="is-disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $citas->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($citas->getUrlRange(1, $citas->lastPage()) as $pagina => $url)
                        <a href="{{ $url }}" class="{{ $pagina == $citas->currentPage() ? 'is-active' : '' }}">{{ $pagina }}</a>
                    @endforeach

                    @if ($citas->hasMorePages())
                        <a href="{{ $citas->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <span class="is-disabled"><i class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
