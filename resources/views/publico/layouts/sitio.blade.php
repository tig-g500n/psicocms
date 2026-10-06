@php
    $esLanding = ($modo ?? 'multipagina') === 'landing';
    $descripcion = $perfil->eslogan ?: frase_publica('hero_subtitulo');

    $navItems = collect([
        ['id' => 'inicio', 'label' => 'Inicio', 'ruta' => 'home', 'activa' => true],
        ['id' => 'sobre-mi', 'label' => 'Sobre mí', 'ruta' => 'publico.sobre-mi', 'activa' => seccion_activa('sobre_mi')],
        ['id' => 'servicios', 'label' => 'Servicios', 'ruta' => 'publico.servicios', 'activa' => seccion_activa('servicios')],
        ['id' => 'blog', 'label' => 'Blog', 'ruta' => 'publico.blog', 'activa' => seccion_activa('blog')],
        ['id' => 'faq', 'label' => 'Preguntas', 'ruta' => 'publico.faq', 'activa' => seccion_activa('faq')],
        ['id' => 'contacto', 'label' => 'Pide cita', 'ruta' => 'publico.contacto', 'activa' => seccion_activa('reservas')],
    ])->where('activa', true);

    $enHome = request()->routeIs('home');
    $ancla = fn ($id) => ($enHome ? '' : route('home')).'#'.$id;
    $enlace = fn ($item) => $esLanding ? $ancla($item['id']) : route($item['ruta']);
    $ctaCita = seccion_activa('reservas') ? ($esLanding ? $ancla('contacto') : route('publico.contacto')) : null;
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', $nombreCompleto.' · Psicología')</title>
    <meta name="description" content="@yield('meta_descripcion', $descripcion)">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('titulo', $nombreCompleto)">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($perfil->foto)
        <meta property="og:image" content="{{ asset($perfil->foto) }}">
    @endif
    @stack('meta')

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/base/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/publico/css/publico.css') }}" rel="stylesheet">
    <style>
        :root {
            --primario: {{ $tema['primario'] }};
            --secundario: {{ $tema['secundario'] }};
            --acento: {{ $tema['acento'] }};
            --fondo: {{ $tema['fondo'] }};
            --superficie: {{ $tema['superficie'] }};
            --texto: {{ $tema['texto'] }};
            --radio: {{ $tema['radio'] }};
        }
    </style>
    @stack('estilos')
</head>

<body class="{{ $esLanding ? 'es-landing' : '' }}">
    <header class="pp-nav" data-nav>
        <div class="pp-wrap pp-nav__inner">
            <a href="{{ $esLanding ? $ancla('inicio') : route('home') }}" class="pp-brand">
                @if (imagen_web('logo'))
                    <img src="{{ imagen_web('logo') }}" alt="{{ $nombreCompleto }}" class="pp-brand__logo">
                @endif
                <span>{{ $nombreCompleto }}</span>
            </a>

            <button type="button" class="pp-nav__burger" data-nav-toggle aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <nav class="pp-menu" data-nav-menu>
                @foreach ($navItems as $item)
                    <a href="{{ $enlace($item) }}" class="pp-menu__link">{{ $item['label'] }}</a>
                @endforeach
                @if ($ctaCita)
                    <a href="{{ $ctaCita }}" class="pp-btn pp-btn--primary pp-menu__cta"><i class="fa-solid fa-calendar-check"></i> Pedir cita</a>
                @endif
            </nav>
        </div>
    </header>

    <main>
        @yield('contenido')
    </main>

    <footer class="pp-footer">
        <div class="pp-wrap pp-footer__inner">
            <div class="pp-footer__brand">
                <strong>{{ $nombreCompleto }}</strong>
                <p>{{ frase_publica('footer_texto') }}</p>
            </div>

            <div class="pp-footer__contacto">
                @if ($perfil->telefono_citas)
                    <a href="tel:{{ $perfil->telefono_citas }}"><i class="fa-solid fa-phone"></i> {{ $perfil->telefono_citas }}</a>
                @endif
                @if ($perfil->email_citas)
                    <a href="mailto:{{ $perfil->email_citas }}"><i class="fa-solid fa-envelope"></i> {{ $perfil->email_citas }}</a>
                @endif
            </div>

            @if (count($redes))
                <div class="pp-footer__redes">
                    @foreach ($redes as $red)
                        <a href="{{ $red['url'] }}" target="_blank" rel="noopener" aria-label="{{ $red['label'] }}">
                            <i class="fa-brands {{ $red['icono'] }}"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="pp-footer__legal">
            <div class="pp-wrap">© {{ date('Y') }} {{ $nombreCompleto }}. Todos los derechos reservados.</div>
        </div>
    </footer>

    <script src="{{ asset('assets/publico/js/publico.js') }}"></script>
    <script src="{{ asset('assets/publico/js/reservas.js') }}"></script>
    @stack('scripts')
</body>

</html>
