@props(['icono' => 'fa-inbox', 'titulo' => 'Nada por aquí todavía', 'texto' => null])

<div class="empty">
    <span class="empty__icon"><i class="fa-solid {{ $icono }}"></i></span>
    <h3 class="empty__title">{{ $titulo }}</h3>
    @if ($texto)
        <p class="empty__text">{{ $texto }}</p>
    @endif
    @isset($accion)
        <div>{{ $accion }}</div>
    @endisset
</div>
