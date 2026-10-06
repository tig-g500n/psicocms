@extends('panel.layouts.app')

@section('titulo', $modo === 'crear' ? 'Nueva pregunta' : 'Editar pregunta')

@php
    $accion = $modo === 'crear' ? route('panel.faq.store') : route('panel.faq.update', $faq);
@endphp

@section('contenido')
    <x-panel.page-header :titulo="$modo === 'crear' ? 'Nueva pregunta frecuente' : 'Editar pregunta frecuente'" subtitulo="La respuesta admite formato con el editor de texto.">
        <x-slot:acciones>
            <a href="{{ route('panel.faq.index') }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="card" style="max-width:80rem;">
        <form method="POST" action="{{ $accion }}">
            @csrf
            @if ($modo === 'editar')
                @method('PUT')
            @endif

            <div class="campo">
                <label class="campo__label" for="pregunta">Pregunta</label>
                <input class="campo__input" type="text" id="pregunta" name="pregunta" value="{{ old('pregunta', $faq->pregunta) }}" required>
            </div>

            <div class="campo">
                <label class="campo__label" for="respuesta">Respuesta</label>
                <x-panel.wysiwyg name="respuesta" :value="old('respuesta', $faq->respuesta)" rows="8" />
            </div>

            <div class="citas-form-grid">
                <div class="campo">
                    <label class="campo__label" for="orden">Orden</label>
                    <input class="campo__input" type="number" id="orden" name="orden" value="{{ old('orden', $faq->orden) }}" min="0">
                    <span class="campo__hint">Número menor = aparece antes.</span>
                </div>

                <div class="campo">
                    <label class="campo__label">Visibilidad</label>
                    <x-panel.toggle name="activo" :checked="old('activo', $faq->activo)" label="Mostrar en la web" />
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:1rem;">
                <a href="{{ route('panel.faq.index') }}" class="btn btn--ghost">Cancelar</a>
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar pregunta</button>
            </div>
        </form>
    </div>
@endsection
