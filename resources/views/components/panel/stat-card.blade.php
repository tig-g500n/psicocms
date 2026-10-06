@props(['icono' => 'fa-chart-simple', 'titulo' => '', 'valor' => '', 'color' => ''])

<div class="stat-card">
    <span class="stat-card__icon {{ $color ? 'stat-card__icon--'.$color : '' }}">
        <i class="fa-solid {{ $icono }}"></i>
    </span>
    <div>
        <p class="stat-card__label">{{ $titulo }}</p>
        <p class="stat-card__value">{{ $valor }}</p>
    </div>
</div>
