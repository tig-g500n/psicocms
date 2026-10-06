@extends('panel.layouts.app')

@section('titulo', $modo === 'crear' ? 'Nuevo paciente' : 'Editar paciente')

@php
    $accion = $modo === 'crear' ? route('panel.pacientes.store') : route('panel.pacientes.update', $paciente);
@endphp

@section('contenido')
    <x-panel.page-header :titulo="$modo === 'crear' ? 'Nuevo paciente' : 'Editar paciente'" subtitulo="El teléfono es el identificador único del paciente.">
        <x-slot:acciones>
            <a href="{{ url()->previous() }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="card" style="max-width:80rem;">
        <form method="POST" action="{{ $accion }}">
            @csrf
            @if ($modo === 'editar')
                @method('PUT')
            @endif

            <div class="citas-form-grid">
                <div class="campo">
                    <label class="campo__label" for="nombre">Nombre completo</label>
                    <input class="campo__input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required>
                </div>

                <div class="campo">
                    <label class="campo__label" for="telefono">Teléfono</label>
                    <input class="campo__input" type="text" id="telefono" name="telefono" value="{{ old('telefono', $paciente->telefono) }}" required>
                    <span class="campo__hint">Identificador único (se guarda sin espacios).</span>
                    @error('telefono') <span class="campo__hint" style="color:var(--dash-danger);">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label class="campo__label" for="email">Email</label>
                    <input class="campo__input" type="email" id="email" name="email" value="{{ old('email', $paciente->email) }}">
                </div>

                <div class="campo">
                    <label class="campo__label" for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input class="campo__input" type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}">
                </div>

                <div class="campo">
                    <label class="campo__label" for="genero">Género</label>
                    <select class="campo__input" id="genero" name="genero">
                        <option value="">Sin especificar</option>
                        @foreach (['Femenino', 'Masculino', 'Otro'] as $g)
                            <option value="{{ $g }}" @selected(old('genero', $paciente->genero) === $g)>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__label" for="modalidad_pref">Modalidad preferida</label>
                    <select class="campo__input" id="modalidad_pref" name="modalidad_pref">
                        <option value="">Sin preferencia</option>
                        <option value="online" @selected(old('modalidad_pref', $paciente->modalidad_pref) === 'online')>Online</option>
                        <option value="presencial" @selected(old('modalidad_pref', $paciente->modalidad_pref) === 'presencial')>Presencial</option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__label" for="estado">Estado</label>
                    <select class="campo__input" id="estado" name="estado" required>
                        @foreach (['activo', 'pausado', 'inactivo'] as $estado)
                            <option value="{{ $estado }}" @selected(old('estado', $paciente->estado ?? 'activo') === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__label" for="direccion">Dirección</label>
                    <input class="campo__input" type="text" id="direccion" name="direccion" value="{{ old('direccion', $paciente->direccion) }}">
                </div>
            </div>

            <div class="campo">
                <label class="campo__label" for="motivo">Motivo de consulta</label>
                <textarea class="campo__input" id="motivo" name="motivo" rows="2">{{ old('motivo', $paciente->motivo) }}</textarea>
            </div>

            <div class="campo">
                <label class="campo__label" for="notas">Notas internas</label>
                <textarea class="campo__input" id="notas" name="notas" rows="4">{{ old('notas', $paciente->notas) }}</textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:1rem;">
                <a href="{{ route('panel.pacientes.index') }}" class="btn btn--ghost">Cancelar</a>
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar paciente</button>
            </div>
        </form>
    </div>
@endsection
