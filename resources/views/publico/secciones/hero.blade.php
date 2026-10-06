@php
    $cita = seccion_activa('reservas') ? ($modo === 'landing' ? '#contacto' : route('publico.contacto')) : null;
@endphp
<section id="inicio" class="pp-hero" @if (imagen_web('hero_fondo')) style="--img-fondo: url('{{ imagen_web('hero_fondo') }}')" @endif>
    <div class="pp-wrap pp-hero__inner">
        <div class="pp-hero__texto">
            @if ($perfil->eslogan)
                <span class="pp-hero__eslogan">{{ $perfil->eslogan }}</span>
            @endif
            <h1 class="pp-hero__titulo">{{ frase_publica('hero_titulo') }}</h1>
            <p class="pp-hero__sub">{{ frase_publica('hero_subtitulo') }}</p>
            @if ($perfil->colegiado)
                <p class="pp-hero__col">Cédula profesional: {{ $perfil->colegiado }}</p>
            @endif

            <div class="pp-hero__acciones">
                @if ($cita)
                    <a href="{{ $cita }}" class="pp-btn pp-btn--primary"><i class="fa-solid fa-calendar-check"></i> {{ frase_publica('boton_cita') }}</a>
                @endif
                @if ($perfil->telefono_citas)
                    <a href="tel:{{ $perfil->telefono_citas }}" class="pp-btn pp-btn--outline"><i class="fa-solid fa-phone"></i> Llamar</a>
                @endif
                @if ($whatsapp)
                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="pp-btn pp-btn--outline"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                @endif
            </div>
        </div>

        @php($fotoHero = $perfil->foto ? asset($perfil->foto) : imagen_web('hero'))
        <div class="pp-hero__figura {{ $fotoHero ? '' : 'pp-hero__figura--placeholder' }}">
            @if ($fotoHero)
                <img src="{{ $fotoHero }}" alt="{{ $nombreCompleto }}">
            @else
                <i class="fa-solid fa-user"></i>
            @endif
        </div>
    </div>
</section>
