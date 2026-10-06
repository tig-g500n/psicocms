@if (seccion_activa('sobre_mi'))
    <section id="sobre-mi" class="pp-seccion pp-sobre">
        <div class="pp-wrap pp-sobre__inner">
            <div class="pp-sobre__figura">
                @if (imagen_web('sobre_mi'))
                    <img src="{{ imagen_web('sobre_mi') }}" alt="Sobre {{ $nombreCompleto }}">
                @endif
                @if (imagen_web('experiencia'))
                    <img src="{{ imagen_web('experiencia') }}" alt="Trayectoria de {{ $nombreCompleto }}" class="pp-sobre__extra" loading="lazy">
                @endif
            </div>
            <div class="pp-sobre__texto">
                <span class="pp-seccion__eyebrow">Sobre mí</span>
                <h2 class="pp-seccion__titulo">{{ $nombreCompleto }}</h2>
                @if (! empty($perfil->sobre_mi))
                    <div class="pp-richtext">{!! $perfil->sobre_mi !!}</div>
                @else
                    <p class="pp-sobre__placeholder">Próximamente encontrarás aquí más información.</p>
                @endif
            </div>
        </div>
    </section>
@endif
