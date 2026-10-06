@extends('instalador.layouts.app')

@section('contenido')
    <div class="inst__head">
        <h2 class="inst__title">Conexión a la base de datos</h2>
        <p class="inst__desc">Introduce los datos de tu servidor MySQL / MariaDB. Crearemos la base de datos y sus tablas automáticamente.</p>
    </div>

    <form method="POST" action="{{ route('instalacion.base-datos') }}" class="inst-form">
        @csrf

        <div class="inst-form__grid">
            <div class="inst-field">
                <label class="inst-field__label" for="host">Servidor (host)</label>
                <input class="inst-field__input" type="text" id="host" name="host" value="{{ old('host', $datos['host']) }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="port">Puerto</label>
                <input class="inst-field__input" type="text" id="port" name="port" value="{{ old('port', $datos['port']) }}" required>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="database">Base de datos</label>
                <input class="inst-field__input" type="text" id="database" name="database" value="{{ old('database', $datos['database']) }}" required>
                <p class="inst-field__hint">Solo letras, números y guion bajo.</p>
            </div>

            <div class="inst-field">
                <label class="inst-field__label" for="username">Usuario</label>
                <input class="inst-field__input" type="text" id="username" name="username" value="{{ old('username', $datos['username']) }}" required>
            </div>

            <div class="inst-field inst-field--full">
                <label class="inst-field__label" for="password">Contraseña</label>
                <input class="inst-field__input" type="password" id="password" name="password" value="{{ old('password') }}" autocomplete="off">
                <p class="inst-field__hint">Déjala vacía si tu MySQL local no tiene contraseña (por defecto en XAMPP).</p>
            </div>
        </div>

        <div class="inst-form__actions">
            <span></span>
            <button type="submit" class="inst-btn inst-btn--primary">
                Probar y continuar <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection
