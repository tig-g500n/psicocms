@extends('panel.layouts.app')

@section('titulo', 'Editar entrada')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/historias.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Editar entrada" :subtitulo="$paciente->nombre.' · '.$entrada->fecha->format('d/m/Y')">
        <x-slot:acciones>
            <a href="{{ route('panel.pacientes.historia', $paciente) }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver a la historia</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div style="max-width:80rem;">
        <div class="card">
            <form method="POST" action="{{ route('panel.historias.update', $entrada) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="campo" style="max-width:24rem;">
                    <label class="campo__label" for="fecha">Fecha de la sesión</label>
                    <input class="campo__input" type="date" id="fecha" name="fecha" value="{{ old('fecha', $entrada->fecha->toDateString()) }}" required>
                </div>

                <div class="campo">
                    <label class="campo__label" for="texto">Anotaciones</label>
                    <x-panel.wysiwyg name="texto" :value="old('texto', $entrada->texto)" rows="8" />
                </div>

                <div class="campo">
                    <label class="campo__label">Añadir adjuntos</label>
                    <label class="hist-uploader" for="adjuntos">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span><strong>Selecciona archivos</strong> · JPG, PNG, WEBP o PDF (máx. 8 MB c/u)</span>
                        <input type="file" id="adjuntos" name="adjuntos[]" accept="image/png,image/jpeg,image/webp,application/pdf" multiple hidden data-file-list="#lista-adjuntos">
                    </label>
                    <div class="hist-filelist" id="lista-adjuntos"></div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:1rem;">
                    <a href="{{ route('panel.pacientes.historia', $paciente) }}" class="btn btn--ghost">Cancelar</a>
                    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
                </div>
            </form>
        </div>

        @if ($entrada->adjuntos->isNotEmpty())
            <div class="card" style="margin-top:2.4rem;">
                <h2 class="card__title" style="margin-bottom:1rem;">Adjuntos actuales</h2>
                <p class="campo__hint" style="margin-bottom:1rem;">Puedes eliminar los adjuntos que ya no necesites.</p>
                @include('panel.historias._adjuntos', ['adjuntos' => $entrada->adjuntos, 'borrable' => true])
            </div>
        @endif
    </div>
@endsection
