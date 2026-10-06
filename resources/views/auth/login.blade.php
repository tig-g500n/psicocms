<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso · {{ config('psicocms.nombre') }}</title>

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/'.config('psicocms.tema_por_defecto').'/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/auth/css/auth.css') }}" rel="stylesheet">
</head>

<body class="auth-body">
    <main class="auth">
        <div class="auth__card">
            <header class="auth__brand">
                <span class="auth__badge"><i class="fa-solid fa-brain"></i></span>
                <h1 class="auth__name">{{ config('psicocms.nombre') }}</h1>
                <p class="auth__sub">Acceso al panel de administración</p>
            </header>

            @if ($errors->any())
                <div class="auth__alert" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('acceso.login') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label class="auth-field__label" for="email">Email</label>
                    <div class="auth-field__control">
                        <i class="fa-solid fa-envelope"></i>
                        <input class="auth-field__input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="auth-field">
                    <label class="auth-field__label" for="telefono">Teléfono</label>
                    <div class="auth-field__control">
                        <i class="fa-solid fa-phone"></i>
                        <input class="auth-field__input" type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" required autocomplete="tel">
                    </div>
                </div>

                <div class="auth-field">
                    <label class="auth-field__label" for="password">Contraseña</label>
                    <div class="auth-field__control">
                        <i class="fa-solid fa-lock"></i>
                        <input class="auth-field__input" type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="auth-field__toggle" data-toggle-password aria-label="Mostrar u ocultar contraseña">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Mantener la sesión iniciada</span>
                </label>

                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar
                </button>
            </form>
        </div>

        <p class="auth__footer">© {{ date('Y') }} {{ config('psicocms.nombre') }}</p>
    </main>

    <script src="{{ asset('assets/auth/js/auth.js') }}"></script>
</body>

</html>
