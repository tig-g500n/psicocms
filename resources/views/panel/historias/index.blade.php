@extends('panel.layouts.app')

@section('titulo', 'Historia de '.$paciente->nombre)

@push('estilos')
<link href="{{ asset('assets/dashboard/css/historias.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header :titulo="'Historia clínica'" :subtitulo="$paciente->nombre.' · '.$paciente->telefono">
        <x-slot:acciones>
            <a href="{{ route('panel.pacientes.show', $paciente) }}" class="btn btn--ghost"><i class="fa-solid fa-arrow-left"></i> Volver al paciente</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="hist">
        <div class="card hist-form">
            <h2 class="card__title" style="margin-bottom:1.6rem;"><i class="fa-solid fa-pen-to-square"></i> Nueva entrada</h2>
            <form method="POST" action="{{ route('panel.pacientes.historia.store', $paciente) }}" enctype="multipart/form-data">
                @csrf
                <div class="campo" style="max-width:24rem;">
                    <label class="campo__label" for="fecha">Fecha de la sesión</label>
                    <input class="campo__input" type="date" id="fecha" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required>
                </div>

                <div class="campo">
                    <label class="campo__label" for="texto">Anotaciones</label>
                    <x-panel.wysiwyg name="texto" :value="old('texto')" rows="8" />
                </div>

                <div class="campo">
                    <label class="campo__label">Adjuntos (fotos o PDF)</label>
                    <label class="hist-uploader" for="adjuntos">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span><strong>Selecciona archivos</strong> · JPG, PNG, WEBP o PDF (máx. 8 MB c/u)</span>
                        <input type="file" id="adjuntos" name="adjuntos[]" accept="image/png,image/jpeg,image/webp,application/pdf" multiple hidden data-file-list="#lista-adjuntos">
                    </label>
                    <div class="hist-filelist" id="lista-adjuntos"></div>
                </div>

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Añadir entrada</button>
                </div>
            </form>
        </div>

        <div class="hist-timeline">
            @forelse ($entradas as $entrada)
                <article class="card hist-entrada">
                    <header class="hist-entrada__head">
                        <div class="hist-entrada__fecha">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $entrada->fecha->translatedFormat('d \d\e F \d\e Y') }}
                        </div>
                        <div class="hist-entrada__acciones">
                            <a href="{{ route('panel.historias.edit', $entrada) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('panel.historias.destroy', $entrada) }}" data-confirm="¿Eliminar esta entrada de la historia? Se borrarán también sus adjuntos.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </header>

                    @if ($entrada->texto)
                        <div class="hist-entrada__texto">{!! $entrada->texto !!}</div>
                    @endif

                    @include('panel.historias._adjuntos', ['adjuntos' => $entrada->adjuntos, 'borrable' => false])
                </article>
            @empty
                <div class="card">
                    <x-panel.empty-state icono="fa-notes-medical" titulo="Historia vacía" texto="Aún no hay entradas. Añade la primera anotación de sesión con el formulario de la izquierda." />
                </div>
            @endforelse
        </div>
    </div>
@endsection
