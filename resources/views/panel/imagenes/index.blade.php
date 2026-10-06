@extends('panel.layouts.app')

@section('titulo', 'Imágenes')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/imagenes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Imágenes de la web"
        subtitulo="Sustituye las imágenes de ejemplo de tu web pública. Las que subas tienen prioridad; puedes volver a la de por defecto cuando quieras.">
    </x-panel.page-header>

    @foreach ($grupos as $grupo)
        <x-panel.card>
            <h2 class="imgs__grupo"><i class="fa-solid {{ $grupo['icono'] }}"></i> {{ $grupo['label'] }}</h2>

            <div class="imgs-grid">
                @foreach ($grupo['items'] as $item)
                    @php
                        $clave = $item['clave'];
                        $personalizada = isset($overrides[$clave]) && is_file(public_path($overrides[$clave]));
                        $ruta = $personalizada ? $overrides[$clave] : $item['defecto'];
                    @endphp
                    <article class="img-slot {{ $personalizada ? 'is-custom' : '' }}">
                        <div class="img-slot__preview">
                            <img src="{{ asset($ruta) }}" alt="{{ $item['label'] }}" loading="lazy">
                            <span class="img-slot__badge {{ $personalizada ? 'is-custom' : '' }}">
                                <i class="fa-solid {{ $personalizada ? 'fa-circle-check' : 'fa-image' }}"></i>
                                {{ $personalizada ? 'Personalizada' : 'Por defecto' }}
                            </span>
                        </div>

                        <div class="img-slot__body">
                            <h3 class="img-slot__title">{{ $item['label'] }}</h3>
                            <p class="img-slot__desc">{{ $item['descripcion'] }}</p>

                            <div class="img-slot__acciones">
                                <form method="POST" action="{{ route('panel.imagenes.actualizar', $clave) }}" enctype="multipart/form-data" class="img-slot__form">
                                    @csrf
                                    <label class="btn btn--primary btn--sm">
                                        <i class="fa-solid fa-upload"></i> Subir imagen
                                        <input type="file" name="imagen" accept="image/png,image/jpeg,image/webp" hidden onchange="this.form.requestSubmit()">
                                    </label>
                                </form>

                                @if ($personalizada)
                                    <form method="POST" action="{{ route('panel.imagenes.restablecer', $clave) }}" data-confirm="¿Restablecer la imagen de «{{ $item['label'] }}» a la de por defecto?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost btn--sm">
                                            <i class="fa-solid fa-rotate-left"></i> Restablecer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </x-panel.card>
    @endforeach

    <p class="imgs__nota">
        <i class="fa-solid fa-circle-info"></i>
        Formatos admitidos: JPG, PNG o WEBP · máximo 4 MB. Estas imágenes se mostrarán en tu web pública cuando esté publicada.
    </p>
@endsection
