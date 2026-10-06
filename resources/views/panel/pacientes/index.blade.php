@extends('panel.layouts.app')

@section('titulo', 'Pacientes')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/pacientes.css') }}" rel="stylesheet">
@endpush

@php
    $badgeEstado = ['activo' => 'green', 'pausado' => 'amber', 'inactivo' => 'danger'];
@endphp

@section('contenido')
    <x-panel.page-header titulo="Gestión de pacientes" subtitulo="Administra tu cartera clínica.">
        <x-slot:acciones>
            <a href="{{ route('panel.pacientes.create') }}" class="btn btn--primary"><i class="fa-solid fa-user-plus"></i> Nuevo paciente</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="stat-grid" style="margin-bottom:2.4rem;">
        <x-panel.stat-card icono="fa-user-group" titulo="Total pacientes" :valor="$estadisticas['total']" />
        <x-panel.stat-card icono="fa-user-check" titulo="Activos" :valor="$estadisticas['activos']" color="green" />
        <x-panel.stat-card icono="fa-clock" titulo="Con cita hoy" :valor="$estadisticas['con_cita_hoy']" color="info" />
    </div>

    <div class="card" style="margin-bottom:2.4rem;">
        <form method="GET" action="{{ route('panel.pacientes.index') }}" class="citas-filtros">
            <div class="citas-filtros__campo">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar por nombre o teléfono">
            </div>
            <select name="estado" class="campo__input">
                <option value="">Estado: todos</option>
                @foreach (['activo', 'pausado', 'inactivo'] as $estado)
                    <option value="{{ $estado }}" @selected(($filtros['estado'] ?? '') === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
            <select name="modalidad" class="campo__input">
                <option value="">Modalidad: todas</option>
                <option value="online" @selected(($filtros['modalidad'] ?? '') === 'online')>Online</option>
                <option value="presencial" @selected(($filtros['modalidad'] ?? '') === 'presencial')>Presencial</option>
            </select>
            <button type="submit" class="btn btn--soft">Filtrar</button>
            @if (array_filter($filtros))
                <a href="{{ route('panel.pacientes.index') }}" class="btn btn--ghost">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if ($pacientes->isEmpty())
            <x-panel.empty-state icono="fa-user-group" titulo="Aún no hay pacientes" texto="Los pacientes que reserven cita se crearán automáticamente. También puedes añadirlos manualmente.">
                <x-slot:accion>
                    <a href="{{ route('panel.pacientes.create') }}" class="btn btn--primary"><i class="fa-solid fa-user-plus"></i> Nuevo paciente</a>
                </x-slot:accion>
            </x-panel.empty-state>
        @else
            <div class="tabla-wrap" style="border:none;box-shadow:none;">
                <table class="tabla">
                    <thead>
                        <tr><th>Paciente</th><th>Última cita</th><th>Próxima cita</th><th>Modalidad</th><th>Estado</th><th style="text-align:right;">Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                            @php($ultima = $paciente->ultimaCita())
                            @php($proxima = $paciente->proximaCita())
                            <tr>
                                <td>
                                    <a href="{{ route('panel.pacientes.show', $paciente) }}" class="pac-fila">
                                        <span class="pac-avatar">{{ mb_strtoupper(mb_substr($paciente->nombre, 0, 1)) }}</span>
                                        <span>
                                            <strong>{{ $paciente->nombre }}</strong><br>
                                            <span class="campo__hint">{{ $paciente->telefono }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td>{{ $ultima ? $ultima->fecha->format('d/m/Y') : '—' }}</td>
                                <td>{{ $proxima ? $proxima->fecha->format('d/m/Y').' · '.$proxima->hora_inicio_corta : 'Sin programar' }}</td>
                                <td>
                                    @if ($paciente->modalidad_pref)
                                        <x-panel.badge :color="$paciente->modalidad_pref === 'online' ? 'info' : 'green'">{{ ucfirst($paciente->modalidad_pref) }}</x-panel.badge>
                                    @else
                                        <span class="campo__hint">—</span>
                                    @endif
                                </td>
                                <td><x-panel.badge :color="$badgeEstado[$paciente->estado] ?? ''">{{ ucfirst($paciente->estado) }}</x-panel.badge></td>
                                <td>
                                    <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                                        <a href="{{ route('panel.pacientes.show', $paciente) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Ver"><i class="fa-solid fa-eye"></i></a>
                                        <a href="{{ route('panel.pacientes.edit', $paciente) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a>
                                        <form method="POST" action="{{ route('panel.pacientes.destroy', $paciente) }}" data-confirm="¿Eliminar a {{ $paciente->nombre }}? Se eliminarán también sus citas.">
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

            @if ($pacientes->hasPages())
                <div class="paginacion">
                    @if ($pacientes->onFirstPage())
                        <span class="is-disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $pacientes->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                    @endif
                    @foreach ($pacientes->getUrlRange(1, $pacientes->lastPage()) as $pagina => $url)
                        <a href="{{ $url }}" class="{{ $pagina == $pacientes->currentPage() ? 'is-active' : '' }}">{{ $pagina }}</a>
                    @endforeach
                    @if ($pacientes->hasMorePages())
                        <a href="{{ $pacientes->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <span class="is-disabled"><i class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
