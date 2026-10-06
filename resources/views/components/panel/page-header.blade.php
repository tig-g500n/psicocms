@props(['titulo', 'subtitulo' => null])

<div class="page-header">
    <div>
        <h1 class="page-header__title">{{ $titulo }}</h1>
        @if ($subtitulo)
            <p class="page-header__subtitle">{{ $subtitulo }}</p>
        @endif
    </div>
    @isset($acciones)
        <div class="page-header__actions">
            {{ $acciones }}
        </div>
    @endisset
</div>
