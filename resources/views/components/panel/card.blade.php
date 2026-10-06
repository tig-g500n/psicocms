@props(['titulo' => null, 'enlace' => null, 'textoEnlace' => 'Ver todo'])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if ($titulo || isset($acciones))
        <div class="card__header">
            @if ($titulo)
                <h2 class="card__title">{{ $titulo }}</h2>
            @endif
            @isset($acciones)
                {{ $acciones }}
            @endisset
            @if ($enlace)
                <a href="{{ $enlace }}" class="card__link">{{ $textoEnlace }}</a>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
