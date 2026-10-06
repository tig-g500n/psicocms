@extends('instalador.layouts.app')

@section('contenido')
    <div class="inst__head">
        <h2 class="inst__title">Foto y apariencia</h2>
        <p class="inst__desc">Sube tu foto y elige el tema visual y el formato de tu web. Todo es editable después.</p>
    </div>

    <form method="POST" action="{{ route('instalacion.finalizar') }}" class="inst-form" enctype="multipart/form-data">
        @csrf

        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-image"></i> Tu foto</h3>
            <label class="inst-foto" for="foto">
                <span class="inst-foto__preview" data-preview>
                    <i class="fa-solid fa-user"></i>
                </span>
                <span class="inst-foto__text">
                    <strong>Haz clic para subir una foto</strong>
                    <span class="inst-field__hint">Preferiblemente sin fondo (PNG). JPG, PNG o WEBP, máx. 4 MB.</span>
                </span>
                <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp" hidden data-foto-input>
            </label>
        </div>

        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-palette"></i> Tema visual</h3>
            <div class="inst-temas">
                @foreach ($temas as $tema)
                    <label class="inst-tema">
                        <input type="radio" name="tema" value="{{ $tema }}" {{ $tema === $temaSeleccionado ? 'checked' : '' }} hidden>
                        <span class="inst-tema__card">
                            <span class="inst-tema__thumb inst-tema__thumb--{{ $tema }}"></span>
                            <span class="inst-tema__name">{{ ucfirst($tema) }}</span>
                            <span class="inst-tema__check"><i class="fa-solid fa-check"></i></span>
                        </span>
                    </label>
                @endforeach
            </div>
            <p class="inst-field__hint">Podrás cambiar de tema cuando quieras desde el panel.</p>
        </div>

        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-window-maximize"></i> Formato de la web</h3>
            <div class="inst-modos">
                <label class="inst-modo">
                    <input type="radio" name="modo" value="multipagina" {{ $modoSeleccionado === 'multipagina' ? 'checked' : '' }} hidden>
                    <span class="inst-modo__card">
                        <i class="fa-solid fa-layer-group"></i>
                        <strong>Multipágina</strong>
                        <span class="inst-field__hint">Secciones navegables con su propia URL.</span>
                    </span>
                </label>
                <label class="inst-modo">
                    <input type="radio" name="modo" value="landing" {{ $modoSeleccionado === 'landing' ? 'checked' : '' }} hidden>
                    <span class="inst-modo__card">
                        <i class="fa-solid fa-scroll"></i>
                        <strong>Landing</strong>
                        <span class="inst-field__hint">Todo en una página con scroll suave.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="inst-form__actions">
            <a href="{{ route('instalacion.servicios') }}" class="inst-btn inst-btn--ghost"><i class="fa-solid fa-arrow-left"></i> Atrás</a>
            <button type="submit" class="inst-btn inst-btn--primary">Finalizar instalación <i class="fa-solid fa-circle-check"></i></button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/instalador/js/instalador.js') }}"></script>
@endpush
