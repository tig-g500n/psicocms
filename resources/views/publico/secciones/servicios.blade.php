@if (seccion_activa('servicios') && $servicios->isNotEmpty())
    <section id="servicios" class="pp-seccion pp-seccion--fondo" @if (imagen_web('servicios_fondo')) style="--img-fondo: url('{{ imagen_web('servicios_fondo') }}')" @endif>
        <div class="pp-wrap">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">Servicios</span>
                <h2 class="pp-seccion__titulo">{{ frase_publica('servicios_titulo') }}</h2>
                <p class="pp-seccion__lead">{{ frase_publica('servicios_subtitulo') }}</p>
            </div>

            <div class="pp-cards">
                @foreach ($servicios as $servicio)
                    <article class="pp-card">
                        <div class="pp-card__icono"><i class="fa-solid {{ $servicio->icono ?: 'fa-heart' }}"></i></div>
                        <h3 class="pp-card__titulo">{{ $servicio->titulo }}</h3>
                        @if ($servicio->descripcion)
                            <p class="pp-card__texto">{{ $servicio->descripcion }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
