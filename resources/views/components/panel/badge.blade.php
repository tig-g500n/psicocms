@props(['color' => '', 'icono' => null])

<span {{ $attributes->merge(['class' => 'badge '.($color ? 'badge--'.$color : '')]) }}>
    @if ($icono)
        <i class="fa-solid {{ $icono }}"></i>
    @endif
    {{ $slot }}
</span>
