@extends('instalador.layouts.app')

@section('contenido')
    <div class="inst__head">
        <h2 class="inst__title">Tu cuenta de acceso</h2>
        <p class="inst__desc">Con estos tres datos accederás al panel. Son privados y únicos: no habrá registro ni más usuarios.</p>
    </div>

    <form method="POST" action="{{ route('instalacion.cuenta') }}" class="inst-form">
        @csrf

        <div class="inst-form__grid">
            <div class="inst-field">
                <label class="inst-field__label" for="nombre">Nombre</label>
                <input class="inst-field__input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $datos['nombre'] ?? '') }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="apellidos">Apellidos</label>
                <input class="inst-field__input" type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $datos['apellidos'] ?? '') }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="email">Email</label>
                <input class="inst-field__input" type="email" id="email" name="email" value="{{ old('email', $datos['email'] ?? '') }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="telefono">Teléfono</label>
                <input class="inst-field__input" type="text" id="telefono" name="telefono" value="{{ old('telefono', $datos['telefono'] ?? '') }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="password">Contraseña</label>
                <input class="inst-field__input" type="password" id="password" name="password" autocomplete="new-password" required>
                <p class="inst-field__hint">Mínimo 8 caracteres.</p>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="password_confirmation">Repite la contraseña</label>
                <input class="inst-field__input" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
            </div>
        </div>

        <div class="inst-form__actions">
            <a href="{{ route('instalacion.base-datos') }}" class="inst-btn inst-btn--ghost"><i class="fa-solid fa-arrow-left"></i> Atrás</a>
            <button type="submit" class="inst-btn inst-btn--primary">Continuar <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
@endsection
