@php($apariencia = dashboard_apariencia())
@php($color = dashboard_color())
<!DOCTYPE html>
<html lang="es" data-tema="{{ $apariencia }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · {{ config('psicocms.nombre') }}</title>

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/'.config('psicocms.tema_por_defecto').'/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/componentes.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/css/apariencia.css') }}" rel="stylesheet">
    <style>
        :root {
            --dash-primary: {{ $color['primary'] }};
            --dash-primary-dark: {{ $color['dark'] }};
        }
    </style>
    @stack('estilos')
</head>

<body>
    <div class="dash">
        @include('panel.partials.sidebar')

        <div class="dash__main">
            @include('panel.partials.header')

            <main class="dash__content">
                @yield('contenido')
            </main>
        </div>

        <div class="dash__overlay" data-sidebar-overlay></div>
    </div>

    <div class="toast-stack" data-toast-stack></div>

    @include('panel.partials.apariencia-modal', ['apariencia' => $apariencia, 'colorActivo' => $color['clave']])

    <div class="modal" id="modal-confirmar" data-modal>
        <div class="modal__overlay" data-modal-close></div>
        <div class="modal__dialog" role="dialog" aria-modal="true">
            <h2 class="modal__title">Confirmar acción</h2>
            <div class="modal__body" data-confirm-text>¿Seguro que quieres continuar?</div>
            <div class="modal__actions">
                <button type="button" class="btn btn--ghost" data-modal-close>Cancelar</button>
                <button type="button" class="btn btn--danger" data-confirm-accept>Sí, continuar</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/apariencia.js') }}"></script>

    @if (session('exito'))
        <script>window.addEventListener('DOMContentLoaded', () => window.PsicoToast?.exito(@json(session('exito'))));</script>
    @endif
    @if (session('error'))
        <script>window.addEventListener('DOMContentLoaded', () => window.PsicoToast?.error(@json(session('error'))));</script>
    @endif

    @stack('scripts')
</body>

</html>
