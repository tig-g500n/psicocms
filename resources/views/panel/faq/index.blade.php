@extends('panel.layouts.app')

@section('titulo', 'Preguntas frecuentes')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/faq.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Preguntas frecuentes" subtitulo="Gestiona las dudas habituales que verán tus pacientes en la web.">
        <x-slot:acciones>
            <a href="{{ route('panel.faq.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva pregunta</a>
        </x-slot:acciones>
    </x-panel.page-header>

    @if ($faqs->isEmpty())
        <div class="card">
            <x-panel.empty-state icono="fa-circle-question" titulo="Sin preguntas frecuentes" texto="Añade las dudas más comunes de tus pacientes para resolverlas en tu web.">
                <x-slot:accion>
                    <a href="{{ route('panel.faq.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Nueva pregunta</a>
                </x-slot:accion>
            </x-panel.empty-state>
        </div>
    @else
        <div class="faq-lista">
            @foreach ($faqs as $faq)
                <div class="faq-item {{ $faq->activo ? '' : 'faq-item--inactivo' }}">
                    <div class="faq-item__head">
                        <span class="faq-item__orden">{{ $faq->orden }}</span>
                        <details class="faq-item__pregunta">
                            <summary>
                                <i class="fa-solid fa-chevron-right"></i>
                                <span>{{ $faq->pregunta }}</span>
                            </summary>
                            <div class="faq-item__respuesta">{!! $faq->respuesta ?: '<em>Sin respuesta.</em>' !!}</div>
                        </details>

                        <div class="faq-item__acciones">
                            <x-panel.badge :color="$faq->activo ? 'green' : 'amber'">{{ $faq->activo ? 'Activa' : 'Oculta' }}</x-panel.badge>
                            <form method="POST" action="{{ route('panel.faq.toggle', $faq) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Activar/ocultar" title="{{ $faq->activo ? 'Ocultar' : 'Activar' }}">
                                    <i class="fa-solid {{ $faq->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('panel.faq.edit', $faq) }}" class="btn btn--icon btn--ghost btn--sm" aria-label="Editar"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('panel.faq.destroy', $faq) }}" data-confirm="¿Eliminar la pregunta «{{ $faq->pregunta }}»?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--icon btn--ghost btn--sm" aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
