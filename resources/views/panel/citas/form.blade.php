@extends('panel.layouts.app')

@section('titulo', $modo === 'crear' ? 'Nueva cita' : 'Editar cita')

@php
    $accion = $modo === 'crear' ? route('panel.citas.store') : route('panel.citas.update', $cita);
    $fechaVal = old('fecha', optional($cita->fecha)->format('Y-m-d'));
    $horaVal = old('hora_inicio', $cita->hora_inicio ? substr($cita->hora_inicio, 0, 5) : '');
@endphp

@section('contenido')
    <x-panel.page-header :titulo="$modo === 'crear' ? 'Nueva cita' : 'Editar cita'" subtitulo="Los horarios disponibles se calculan según tu disponibilidad, descansos y vacaciones.">
        <x-slot:acciones>
            <a href="{{ route('panel.citas.index') }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="card" style="max-width:72rem;">
        <form method="POST" action="{{ $accion }}"
              data-cita-form
              data-huecos-url="{{ route('panel.citas.huecos') }}"
              data-buscar-url="{{ route('panel.pacientes.buscar') }}"
              data-except="{{ $modo === 'editar' ? $cita->id : '' }}"
              data-hora-actual="{{ $horaVal }}">
            @csrf
            @if ($modo === 'editar')
                @method('PUT')
            @endif

            <div class="citas-form-grid">
                <div class="campo autocomplete">
                    <label class="campo__label" for="paciente_nombre">Nombre del paciente</label>
                    <input class="campo__input" type="text" id="paciente_nombre" name="paciente_nombre" value="{{ old('paciente_nombre', $paciente->nombre ?? '') }}" autocomplete="off" data-paciente-nombre required>
                    <div class="autocomplete__lista" data-sugerencias hidden></div>
                    <span class="campo__hint">Empieza a escribir para buscar pacientes existentes.</span>
                </div>

                <div class="campo">
                    <label class="campo__label" for="paciente_telefono">Teléfono</label>
                    <input class="campo__input" type="text" id="paciente_telefono" name="paciente_telefono" value="{{ old('paciente_telefono', $paciente->telefono ?? '') }}" data-paciente-telefono required>
                    <span class="campo__hint">Se usa como identificador del paciente.</span>
                </div>

                <div class="campo">
                    <label class="campo__label" for="modalidad">Modalidad</label>
                    <select class="campo__input" id="modalidad" name="modalidad" data-modalidad required>
                        <option value="online" @selected(old('modalidad', $cita->modalidad) === 'online')>Online</option>
                        <option value="presencial" @selected(old('modalidad', $cita->modalidad) === 'presencial')>Presencial</option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__label" for="estado">Estado</label>
                    <select class="campo__input" id="estado" name="estado" required>
                        @foreach (['pendiente', 'confirmada', 'realizada', 'cancelada'] as $estado)
                            <option value="{{ $estado }}" @selected(old('estado', $cita->estado) === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__label" for="fecha">Fecha</label>
                    <input class="campo__input" type="date" id="fecha" name="fecha" value="{{ $fechaVal }}" data-fecha required>
                </div>

                <div class="campo">
                    <label class="campo__label" for="hora_inicio">Hora</label>
                    <select class="campo__input" id="hora_inicio" name="hora_inicio" data-hora required>
                        @if ($horaVal)
                            <option value="{{ $horaVal }}" selected>{{ $horaVal }}</option>
                        @else
                            <option value="">Elige fecha y modalidad primero</option>
                        @endif
                    </select>
                    <span class="campo__hint" data-hora-aviso></span>
                    @error('hora_inicio') <span class="campo__hint" style="color:var(--dash-danger);">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="campo">
                <label class="campo__label" for="notas">Notas (opcional)</label>
                <textarea class="campo__input" id="notas" name="notas" rows="4">{{ old('notas', $cita->notas) }}</textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:1rem;">
                <a href="{{ route('panel.citas.index') }}" class="btn btn--ghost">Cancelar</a>
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar cita</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/dashboard/js/citas-form.js') }}"></script>
@endpush
