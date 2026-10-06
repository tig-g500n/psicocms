@extends('panel.layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <x-panel.page-header
        :titulo="'¡Hola, '.auth()->user()->nombre.'!'"
        subtitulo="Aquí tienes un resumen de tu actividad.">
        <x-slot:acciones>
            <a href="{{ route('panel.citas.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva cita</a>
        </x-slot:acciones>
    </x-panel.page-header>

    @if ($modoVacaciones)
        <div class="card" style="border-left:.4rem solid var(--dash-warning);margin-bottom:2.4rem;display:flex;align-items:center;gap:1.2rem;">
            <i class="fa-solid fa-plane-departure" style="color:var(--dash-warning);font-size:2rem;"></i>
            <div><strong>Modo vacaciones activo.</strong> El sistema de reservas está pausado. Puedes desactivarlo en <a href="{{ route('panel.disponibilidad.index') }}" class="card__link">Disponibilidad</a>.</div>
        </div>
    @endif

    <div class="stat-grid" style="margin-bottom:2.4rem;">
        <x-panel.stat-card icono="fa-calendar-day" titulo="Citas de hoy" :valor="$estadisticas['citas_hoy']" />
        <x-panel.stat-card icono="fa-user-group" titulo="Pacientes activos" :valor="$estadisticas['pacientes']" color="green" />
        <x-panel.stat-card icono="fa-calendar-check" titulo="Citas este mes" :valor="$estadisticas['citas_mes']" color="info" />
        <x-panel.stat-card icono="fa-hourglass-half" titulo="Pendientes" :valor="$estadisticas['citas_pendientes']" color="amber" />
    </div>

    <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:2.4rem;align-items:start;" class="inicio-cols">
        <x-panel.card titulo="Próximas citas" :enlace="route('panel.citas.index')" textoEnlace="Ver todas">
            @if ($proximas->isEmpty())
                <x-panel.empty-state icono="fa-calendar-xmark" titulo="Sin citas próximas" texto="Cuando agendes citas aparecerán aquí. Crea la primera con «Nueva cita»." />
            @else
                <div class="tabla-wrap" style="border:none;box-shadow:none;">
                    <table class="tabla">
                        <thead>
                            <tr><th>Fecha</th><th>Hora</th><th>Paciente</th><th>Modalidad</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($proximas as $cita)
                                <tr>
                                    <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                    <td>{{ $cita->hora_inicio_corta }}</td>
                                    <td>{{ $cita->paciente->nombre ?? '—' }}</td>
                                    <td>
                                        <x-panel.badge :color="$cita->modalidad === 'online' ? 'info' : 'green'">
                                            {{ ucfirst($cita->modalidad) }}
                                        </x-panel.badge>
                                    </td>
                                    <td><x-panel.badge :color="['pendiente'=>'amber','confirmada'=>'green','realizada'=>'info','cancelada'=>'danger'][$cita->estado] ?? ''">{{ ucfirst($cita->estado) }}</x-panel.badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-panel.card>

        <x-panel.card titulo="Disponibilidad online" :enlace="route('panel.disponibilidad.index')" textoEnlace="Configurar">
            <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:1rem;">
                @foreach ($gridOnline as $dia => $info)
                    @php
                        $marcadas = collect($info['horas'])->where('marcado', true)->pluck('hora');
                    @endphp
                    <li style="display:flex;align-items:center;justify-content:space-between;font-size:1.45rem;">
                        <span style="color:var(--dash-text-soft);">{{ $info['nombre'] }}</span>
                        @if ($marcadas->isEmpty())
                            <span style="color:var(--dash-text-soft);font-style:italic;">No disponible</span>
                        @else
                            <strong>{{ $marcadas->first() }} - {{ \Illuminate\Support\Str::of($marcadas->last())->toString() }}</strong>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-panel.card>
    </div>
@endsection
