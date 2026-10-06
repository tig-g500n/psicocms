@php
    $perfil = $perfil ?? new \App\Models\PerfilPublico();
    $nombreCompleto = trim(($perfil->nombre ?? '').' '.($perfil->apellidos ?? ''));
    $nombreCompleto = $nombreCompleto !== '' ? $nombreCompleto : 'Tu nombre y apellidos';
    $eslogan = $perfil->eslogan ?? 'Aquí aparecerá tu frase o eslogan.';
    $esLanding = $modo === 'landing';
    $secciones = [
        ['id' => 'inicio', 'label' => 'Inicio'],
        ['id' => 'sobre-mi', 'label' => 'Sobre mí'],
        ['id' => 'servicios', 'label' => 'Servicios'],
        ['id' => 'especialidades', 'label' => 'Especialidades'],
        ['id' => 'planes', 'label' => 'Planes'],
        ['id' => 'contacto', 'label' => 'Contacto'],
    ];
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista previa · {{ $tema['nombre'] }} · {{ config('psicocms.nombre') }}</title>
    <meta name="robots" content="noindex, nofollow">

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/base/css/fonts.css') }}" rel="stylesheet">

    <style>
        :root {
            --primario: {{ $tema['primario'] }};
            --secundario: {{ $tema['secundario'] }};
            --acento: {{ $tema['acento'] }};
            --fondo: {{ $tema['fondo'] }};
            --superficie: {{ $tema['superficie'] }};
            --texto: {{ $tema['texto'] }};
            --radio: {{ $tema['radio'] }};
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 62.5%; scroll-behavior: smooth; }

        body {
            font-family: "{{ $tema['fuente'] }}", sans-serif;
            font-size: 1.6rem;
            line-height: 1.6;
            color: var(--texto);
            background: var(--fondo);
        }

        img { max-width: 100%; display: block; }
        a { color: inherit; text-decoration: none; }

        .pv-aviso {
            position: sticky;
            top: 0;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            flex-wrap: wrap;
            padding: 1rem 2rem;
            background: #1f2937;
            color: #fff;
            font-size: 1.35rem;
            text-align: center;
        }

        .pv-aviso strong { color: #ffd8a8; }
        .pv-aviso__badge {
            padding: .2rem 1rem;
            border-radius: 5rem;
            background: rgba(255, 255, 255, .15);
            text-transform: capitalize;
        }

        .pv-wrap { max-width: 116rem; margin: 0 auto; padding: 0 2.4rem; }

        .pv-nav {
            position: sticky;
            top: 3.8rem;
            z-index: 20;
            background: var(--superficie);
            box-shadow: 0 .2rem 1.2rem rgba(0, 0, 0, .06);
        }

        .pv-nav__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            padding: 1.6rem 0;
        }

        .pv-brand {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--primario);
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .pv-brand i { color: var(--acento); }

        .pv-menu {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .pv-menu a {
            font-size: 1.4rem;
            color: var(--texto);
            opacity: .8;
            padding-bottom: .3rem;
            border-bottom: .2rem solid transparent;
            transition: .15s;
        }

        .pv-menu a:hover { opacity: 1; border-color: var(--primario); }

        .pv-btn {
            display: inline-flex;
            align-items: center;
            gap: .8rem;
            padding: 1.2rem 2.2rem;
            border-radius: var(--radio);
            font-size: 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: .15s;
        }

        .pv-btn--primary { background: var(--primario); color: #fff; }
        .pv-btn--primary:hover { background: var(--acento); }
        .pv-btn--outline { background: transparent; color: var(--primario); border: .2rem solid var(--primario); }
        .pv-btn--outline:hover { background: var(--primario); color: #fff; }

        .pv-section { padding: 7rem 0; }
        .pv-section:nth-child(even) { background: var(--superficie); }

        .pv-section__title {
            font-size: 3.2rem;
            color: var(--primario);
            text-align: center;
            margin-bottom: 1rem;
        }

        .pv-section__lead {
            text-align: center;
            max-width: 62rem;
            margin: 0 auto 4rem;
            opacity: .75;
        }

        .pv-hero {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            align-items: center;
            gap: 4rem;
            padding: 8rem 0;
        }

        .pv-hero__eslogan {
            display: inline-block;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--acento);
            background: var(--secundario);
            padding: .6rem 1.6rem;
            border-radius: 5rem;
            margin-bottom: 2rem;
        }

        .pv-hero__title { font-size: 4.4rem; line-height: 1.15; color: var(--primario); margin-bottom: 1.6rem; }
        .pv-hero__sub { font-size: 1.7rem; opacity: .8; margin-bottom: 1rem; }
        .pv-hero__colegiado { font-size: 1.4rem; opacity: .65; margin-bottom: 2.8rem; }
        .pv-hero__acciones { display: flex; gap: 1.2rem; flex-wrap: wrap; }

        .pv-hero__figura {
            aspect-ratio: 1;
            border-radius: var(--radio);
            background: linear-gradient(135deg, var(--secundario), var(--primario));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2rem 4rem rgba(0, 0, 0, .12);
        }

        .pv-hero__figura img { width: 100%; height: 100%; object-fit: cover; }
        .pv-hero__figura i { font-size: 9rem; color: rgba(255, 255, 255, .85); }

        .pv-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(26rem, 1fr)); gap: 2.4rem; }

        .pv-card {
            background: var(--fondo);
            border-radius: var(--radio);
            padding: 3rem;
            border: .1rem solid rgba(0, 0, 0, .05);
        }

        .pv-section:nth-child(even) .pv-card { background: var(--superficie); border-color: rgba(0, 0, 0, .07); }

        .pv-card__icon {
            width: 5.4rem;
            height: 5.4rem;
            border-radius: 1.4rem;
            background: var(--secundario);
            color: var(--acento);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 1.6rem;
        }

        .pv-card__title { font-size: 2rem; color: var(--primario); margin-bottom: .8rem; }
        .pv-card__text { font-size: 1.45rem; opacity: .8; }

        .pv-chips { display: flex; flex-wrap: wrap; gap: 1.2rem; justify-content: center; }

        .pv-chip {
            padding: 1rem 2rem;
            background: var(--secundario);
            color: var(--acento);
            border-radius: 5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .pv-plan { text-align: center; }
        .pv-plan__modalidad {
            display: inline-block;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: .1rem;
            color: var(--acento);
            margin-bottom: 1rem;
        }
        .pv-plan__precio { font-size: 3.4rem; font-weight: 700; color: var(--primario); margin: 1rem 0; }
        .pv-plan__precio span { font-size: 1.5rem; font-weight: 400; opacity: .7; }
        .pv-plan__lista { list-style: none; display: inline-flex; flex-direction: column; gap: .8rem; text-align: left; margin-top: 1.4rem; }
        .pv-plan__lista li { display: flex; align-items: flex-start; gap: .8rem; font-size: 1.4rem; opacity: .85; }
        .pv-plan__lista li i { flex-shrink: 0; color: var(--acento); margin-top: .4rem; font-size: 1.1rem; }

        .pv-sobre { max-width: 72rem; margin: 0 auto; font-size: 1.65rem; line-height: 1.8; opacity: .9; }
        .pv-sobre :is(p, ul, ol) { margin-bottom: 1.4rem; }
        .pv-sobre ul, .pv-sobre ol { padding-left: 2.4rem; }

        .pv-contacto { display: grid; grid-template-columns: repeat(auto-fit, minmax(24rem, 1fr)); gap: 2.4rem; }
        .pv-contacto__item { display: flex; gap: 1.4rem; align-items: flex-start; }
        .pv-contacto__item i { color: var(--primario); font-size: 2rem; margin-top: .4rem; }
        .pv-contacto__item strong { display: block; font-size: 1.5rem; margin-bottom: .2rem; }
        .pv-contacto__item span { font-size: 1.45rem; opacity: .8; }

        .pv-empty { text-align: center; opacity: .55; font-size: 1.5rem; padding: 2rem; }

        .pv-footer { background: var(--primario); color: #fff; text-align: center; padding: 4rem 2rem; font-size: 1.4rem; }
        .pv-footer strong { display: block; font-size: 1.8rem; margin-bottom: .6rem; }

        @media (max-width: 820px) {
            .pv-hero { grid-template-columns: 1fr; text-align: center; padding: 5rem 0; }
            .pv-hero__figura { max-width: 34rem; margin: 0 auto; }
            .pv-hero__acciones { justify-content: center; }
            .pv-menu { display: none; }
            .pv-hero__title { font-size: 3.4rem; }
        }
    </style>
</head>

<body>
    <div class="pv-aviso">
        <span><i class="fa-solid fa-circle-info"></i> Vista previa del tema <strong>{{ $tema['nombre'] }}</strong> con tus datos actuales.</span>
        <span class="pv-aviso__badge"><i class="fa-solid fa-window-maximize"></i> {{ $esLanding ? 'Landing' : 'Multipágina' }}</span>
    </div>

    <header class="pv-nav">
        <div class="pv-wrap pv-nav__inner">
            <a href="#inicio" class="pv-brand"><i class="fa-solid fa-brain"></i> {{ $nombreCompleto }}</a>
            <nav class="pv-menu">
                @foreach ($secciones as $s)
                    <a href="#{{ $s['id'] }}">{{ $s['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    <section id="inicio">
        <div class="pv-wrap pv-hero">
            <div>
                <span class="pv-hero__eslogan">{{ $eslogan }}</span>
                <h1 class="pv-hero__title">{{ $nombreCompleto }}</h1>
                <p class="pv-hero__sub">Psicología para acompañarte en tu proceso de bienestar.</p>
                @if (! empty($perfil->colegiado))
                    <p class="pv-hero__colegiado">Cédula profesional: {{ $perfil->colegiado }}</p>
                @endif
                <div class="pv-hero__acciones">
                    <a href="#contacto" class="pv-btn pv-btn--primary"><i class="fa-solid fa-calendar-check"></i> Pedir cita</a>
                    @if (! empty($perfil->telefono_citas))
                        <a href="tel:{{ $perfil->telefono_citas }}" class="pv-btn pv-btn--outline"><i class="fa-solid fa-phone"></i> Llamar</a>
                    @endif
                </div>
            </div>
            <div class="pv-hero__figura">
                @if (! empty($perfil->foto))
                    <img src="{{ asset($perfil->foto) }}" alt="{{ $nombreCompleto }}">
                @elseif (imagen_web('hero'))
                    <img src="{{ imagen_web('hero') }}" alt="{{ $nombreCompleto }}">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>
        </div>
    </section>

    <section id="sobre-mi" class="pv-section">
        <div class="pv-wrap">
            <h2 class="pv-section__title">Sobre mí</h2>
            @if (! empty($perfil->sobre_mi))
                <div class="pv-sobre">{!! $perfil->sobre_mi !!}</div>
            @else
                <p class="pv-empty">Añade tu presentación desde «Información pública».</p>
            @endif
        </div>
    </section>

    <section id="servicios" class="pv-section">
        <div class="pv-wrap">
            <h2 class="pv-section__title">Servicios</h2>
            <p class="pv-section__lead">Formas de acompañarte adaptadas a lo que necesitas.</p>
            @if ($servicios->isNotEmpty())
                <div class="pv-cards">
                    @foreach ($servicios as $servicio)
                        <article class="pv-card">
                            <div class="pv-card__icon"><i class="fa-solid {{ $servicio->icono ?: 'fa-heart' }}"></i></div>
                            <h3 class="pv-card__title">{{ $servicio->titulo }}</h3>
                            @if ($servicio->descripcion)
                                <p class="pv-card__text">{{ $servicio->descripcion }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p class="pv-empty">Añade tus servicios desde «Información pública».</p>
            @endif
        </div>
    </section>

    <section id="especialidades" class="pv-section">
        <div class="pv-wrap">
            <h2 class="pv-section__title">Especialidades</h2>
            @if ($especialidades->isNotEmpty())
                <div class="pv-chips">
                    @foreach ($especialidades as $especialidad)
                        <span class="pv-chip">{{ $especialidad->titulo }}</span>
                    @endforeach
                </div>
            @else
                <p class="pv-empty">Añade tus especialidades desde «Información pública».</p>
            @endif
        </div>
    </section>

    <section id="planes" class="pv-section">
        <div class="pv-wrap">
            <h2 class="pv-section__title">Planes y precios</h2>
            <p class="pv-section__lead">Tarifas claras para sesiones online y presenciales.</p>
            @if ($planes->isNotEmpty())
                <div class="pv-cards">
                    @foreach ($planes as $plan)
                        <article class="pv-card pv-plan">
                            <span class="pv-plan__modalidad">{{ ucfirst($plan->modalidad) }}</span>
                            <h3 class="pv-card__title">{{ $plan->titulo }}</h3>
                            @if ($plan->precio)
                                <div class="pv-plan__precio">{{ precio_mxn($plan->precio) }}</div>
                            @endif
                            @php($incluye = lista_caracteristicas($plan->caracteristicas))
                            @if (count($incluye))
                                <ul class="pv-plan__lista">
                                    @foreach ($incluye as $item)
                                        <li><i class="fa-solid fa-check"></i> {{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p class="pv-empty">Añade tus planes desde «Información pública».</p>
            @endif
        </div>
    </section>

    <section id="contacto" class="pv-section">
        <div class="pv-wrap">
            <h2 class="pv-section__title">¿Dónde estamos?</h2>
            <p class="pv-section__lead">Pide tu cita o pásate por la consulta.</p>
            <div class="pv-contacto">
                @if (! empty($perfil->direccion))
                    <div class="pv-contacto__item">
                        <i class="fa-solid fa-location-dot"></i>
                        <div><strong>Dirección</strong><span>{{ $perfil->direccion }}</span></div>
                    </div>
                @endif
                @if (! empty($perfil->lugar_consulta))
                    <div class="pv-contacto__item">
                        <i class="fa-solid fa-building"></i>
                        <div><strong>Consulta</strong><span>{{ $perfil->lugar_consulta }}</span></div>
                    </div>
                @endif
                @if (! empty($perfil->telefono_citas))
                    <div class="pv-contacto__item">
                        <i class="fa-solid fa-phone"></i>
                        <div><strong>Teléfono</strong><span>{{ $perfil->telefono_citas }}</span></div>
                    </div>
                @endif
                @if (! empty($perfil->email_citas))
                    <div class="pv-contacto__item">
                        <i class="fa-solid fa-envelope"></i>
                        <div><strong>Email</strong><span>{{ $perfil->email_citas }}</span></div>
                    </div>
                @endif
            </div>
            @if (empty($perfil->direccion) && empty($perfil->lugar_consulta) && empty($perfil->telefono_citas) && empty($perfil->email_citas))
                <p class="pv-empty">Añade tus datos de contacto desde «Información pública».</p>
            @endif
        </div>
    </section>

    <footer class="pv-footer">
        <strong>{{ $nombreCompleto }}</strong>
        <span>Vista previa generada por {{ config('psicocms.nombre') }}</span>
    </footer>
</body>

</html>
