@if (seccion_activa('planes') && $planes->isNotEmpty())
    <section id="planes" class="pp-seccion pp-seccion--alt">
        <div class="pp-wrap">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">Planes y precios</span>
                <h2 class="pp-seccion__titulo">Tarifas claras</h2>
            </div>

            <div class="pp-cards">
                @foreach ($planes as $plan)
                    <article class="pp-plan">
                        <span class="pp-plan__modalidad">
                            <i class="fa-solid {{ $plan->modalidad === 'online' ? 'fa-video' : 'fa-location-dot' }}"></i>
                            {{ ucfirst($plan->modalidad) }}
                        </span>
                        <h3 class="pp-plan__titulo">{{ $plan->titulo }}</h3>
                        @if ($plan->precio)
                            <div class="pp-plan__precio">{{ precio_mxn($plan->precio) }}</div>
                        @endif
                        @php($incluye = lista_caracteristicas($plan->caracteristicas))
                        @if (count($incluye))
                            <ul class="pp-plan__lista">
                                @foreach ($incluye as $item)
                                    <li><i class="fa-solid fa-check"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
