@extends('instalador.layouts.app')

@section('contenido')
    <div class="inst__head">
        <h2 class="inst__title">Información pública</h2>
        <p class="inst__desc">Estos datos se mostrarán en tu web. Podrás ampliarlos y editarlos después desde el panel.</p>
    </div>

    <form method="POST" action="{{ route('instalacion.perfil') }}" class="inst-form">
        @csrf

        <div class="inst-form__grid">
            <div class="inst-field inst-field--full">
                <label class="inst-field__label" for="eslogan">Frase gancho o eslogan</label>
                <input class="inst-field__input" type="text" id="eslogan" name="eslogan" value="{{ old('eslogan', $datos['eslogan'] ?? '') }}" placeholder="Ej. Acompañándote hacia tu bienestar">
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="num_colegiado">Cédula profesional</label>
                <input class="inst-field__input" type="text" id="num_colegiado" name="num_colegiado" value="{{ old('num_colegiado', $datos['num_colegiado'] ?? '') }}">
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="telefono_citas">Teléfono para citas</label>
                <input class="inst-field__input" type="text" id="telefono_citas" name="telefono_citas" value="{{ old('telefono_citas', $datos['telefono_citas'] ?? '') }}">
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="email_citas">Email para citas</label>
                <input class="inst-field__input" type="email" id="email_citas" name="email_citas" value="{{ old('email_citas', $datos['email_citas'] ?? '') }}">
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="lugar_consulta">Lugar de consulta</label>
                <input class="inst-field__input" type="text" id="lugar_consulta" name="lugar_consulta" value="{{ old('lugar_consulta', $datos['lugar_consulta'] ?? '') }}">
            </div>

            <div class="inst-field inst-field--full">
                <label class="inst-field__label" for="direccion">Dirección</label>
                <input class="inst-field__input" type="text" id="direccion" name="direccion" value="{{ old('direccion', $datos['direccion'] ?? '') }}">
            </div>

            <div class="inst-field inst-field--full">
                <label class="inst-field__label" for="sobre_mi">Sobre mí</label>
                <textarea class="inst-field__input inst-field__textarea" id="sobre_mi" name="sobre_mi" rows="5" placeholder="Cuenta brevemente tu enfoque y experiencia.">{{ old('sobre_mi', $datos['sobre_mi'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="inst-form__actions">
            <a href="{{ route('instalacion.cuenta') }}" class="inst-btn inst-btn--ghost"><i class="fa-solid fa-arrow-left"></i> Atrás</a>
            <button type="submit" class="inst-btn inst-btn--primary">Continuar <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
@endsection
