<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instalación · {{ config('psicocms.nombre') }}</title>

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/'.config('psicocms.tema_por_defecto').'/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/instalador/css/instalador.css') }}" rel="stylesheet">
    @stack('estilos')
</head>

<body class="inst-body">
    <main class="inst">
        <header class="inst__brand">
            <span class="inst__brand-badge"><i class="fa-solid fa-brain"></i></span>
            <div>
                <h1 class="inst__brand-name">{{ config('psicocms.nombre') }}</h1>
                <p class="inst__brand-sub">Asistente de instalación</p>
            </div>
        </header>

        @isset($paso)
            @include('instalador.partials.pasos', ['actual' => $paso])
        @endisset

        @if (session('error'))
            <div class="inst__alert inst__alert--error" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="inst__alert inst__alert--error" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <p>Revisa los siguientes campos:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <section class="inst__card">
            @yield('contenido')
        </section>

        <footer class="inst__footer">
            <p>© {{ date('Y') }} {{ config('psicocms.nombre') }}</p>
        </footer>
    </main>

    @stack('scripts')
</body>

</html>
