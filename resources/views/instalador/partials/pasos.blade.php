@php
    $listaPasos = [
        1 => ['icono' => 'fa-database', 'texto' => 'Base de datos'],
        2 => ['icono' => 'fa-user-lock', 'texto' => 'Cuenta'],
        3 => ['icono' => 'fa-address-card', 'texto' => 'Perfil'],
        4 => ['icono' => 'fa-list-check', 'texto' => 'Servicios'],
        5 => ['icono' => 'fa-palette', 'texto' => 'Apariencia'],
    ];
@endphp

<nav class="inst-steps" aria-label="Progreso de instalación">
    @foreach ($listaPasos as $numero => $info)
        <div class="inst-steps__item {{ $numero < $actual ? 'is-done' : '' }} {{ $numero === $actual ? 'is-active' : '' }}">
            <span class="inst-steps__dot">
                @if ($numero < $actual)
                    <i class="fa-solid fa-check"></i>
                @else
                    <i class="fa-solid {{ $info['icono'] }}"></i>
                @endif
            </span>
            <span class="inst-steps__label">{{ $info['texto'] }}</span>
        </div>
        @if (! $loop->last)
            <span class="inst-steps__line {{ $numero < $actual ? 'is-done' : '' }}"></span>
        @endif
    @endforeach
</nav>
