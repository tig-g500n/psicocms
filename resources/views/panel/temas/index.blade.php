@extends('panel.layouts.app')

@section('titulo', 'Temas visuales')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/temas.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Temas visuales"
        subtitulo="Elige la apariencia de tu web pública y su formato. El cambio se aplica al instante.">
    </x-panel.page-header>

    <form method="POST" action="{{ route('panel.temas.activar') }}" data-temas-form>
        @csrf

        <x-panel.card>
            <h2 class="temas__subtitulo"><i class="fa-solid fa-window-maximize"></i> Formato de la web</h2>
            <p class="temas__ayuda">Define cómo se recorre tu web. Puedes cambiarlo cuando quieras.</p>
            <div class="temas-modos">
                <label class="temas-modo">
                    <input type="radio" name="modo" value="multipagina" {{ $modo === 'multipagina' ? 'checked' : '' }} data-modo hidden>
                    <span class="temas-modo__card">
                        <i class="fa-solid fa-layer-group"></i>
                        <strong>Multipágina</strong>
                        <span class="temas-modo__hint">Secciones navegables, cada una con su propia URL.</span>
                    </span>
                </label>
                <label class="temas-modo">
                    <input type="radio" name="modo" value="landing" {{ $modo === 'landing' ? 'checked' : '' }} data-modo hidden>
                    <span class="temas-modo__card">
                        <i class="fa-solid fa-scroll"></i>
                        <strong>Landing</strong>
                        <span class="temas-modo__hint">Todo en una sola página con scroll suave.</span>
                    </span>
                </label>
            </div>
            <div class="temas-formato__accion">
                <button type="submit" name="tema" value="{{ $activo }}" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar formato
                </button>
                <span class="temas-modo__hint">Aplica el formato al tema activo sin cambiar de tema.</span>
            </div>
        </x-panel.card>

        <div class="temas-grid">
            @foreach ($temas as $slug => $tema)
                <article class="tema-card {{ $slug === $activo ? 'is-active' : '' }}">
                    @if ($slug === $activo)
                        <span class="tema-card__activo"><i class="fa-solid fa-circle-check"></i> Activo</span>
                    @endif

                    <div class="tema-card__mock" style="background: {{ $tema['fondo'] }};">
                        <span class="tema-card__mock-bar" style="background: {{ $tema['primario'] }};"></span>
                        <span class="tema-card__mock-hero" style="background: {{ $tema['secundario'] }};"></span>
                        <span class="tema-card__mock-line" style="background: {{ $tema['primario'] }};"></span>
                        <span class="tema-card__mock-line tema-card__mock-line--short" style="background: {{ $tema['texto'] }};"></span>
                        <span class="tema-card__mock-chip" style="background: {{ $tema['acento'] }};"></span>
                    </div>

                    <div class="tema-card__body">
                        <div class="tema-card__head">
                            <h3 class="tema-card__nombre">{{ $tema['nombre'] }}</h3>
                            <span class="tema-card__swatches" aria-hidden="true">
                                <span style="background: {{ $tema['primario'] }};"></span>
                                <span style="background: {{ $tema['secundario'] }};"></span>
                                <span style="background: {{ $tema['acento'] }};"></span>
                            </span>
                        </div>
                        <p class="tema-card__desc">{{ $tema['descripcion'] }}</p>

                        <div class="tema-card__acciones">
                            <a href="{{ route('panel.temas.preview', $slug) }}" target="_blank" rel="noopener"
                               class="btn btn--ghost btn--sm" data-preview-link data-preview-base="{{ route('panel.temas.preview', $slug) }}">
                                <i class="fa-solid fa-eye"></i> Previsualizar
                            </a>
                            @if ($slug === $activo)
                                <span class="btn btn--soft btn--sm is-disabled"><i class="fa-solid fa-check"></i> Tema activo</span>
                            @else
                                <button type="submit" name="tema" value="{{ $slug }}" class="btn btn--primary btn--sm">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Activar
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashboard/js/temas.js') }}"></script>
@endpush
