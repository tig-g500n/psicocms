@php
    $directos = [
        ['slug' => 'inicio', 'label' => 'Inicio', 'icono' => 'fa-gauge-high', 'ruta' => 'panel.inicio', 'patron' => 'panel.inicio'],
        ['slug' => 'citas', 'label' => 'Citas', 'icono' => 'fa-calendar-check', 'ruta' => 'panel.citas.index', 'patron' => 'panel.citas.*'],
        ['slug' => 'calendario', 'label' => 'Calendario', 'icono' => 'fa-calendar-days', 'ruta' => 'panel.calendario.index', 'patron' => 'panel.calendario.*'],
        ['slug' => 'pacientes', 'label' => 'Pacientes', 'icono' => 'fa-user-group', 'ruta' => 'panel.pacientes.index', 'patron' => 'panel.pacientes.*'],
        ['slug' => 'historias', 'label' => 'Historias', 'icono' => 'fa-notes-medical', 'ruta' => 'panel.historias.index', 'patron' => 'panel.historias.*'],
        ['slug' => 'blog', 'label' => 'Blog', 'icono' => 'fa-newspaper', 'ruta' => 'panel.blog.articulos.index', 'patron' => 'panel.blog.*'],
    ];

    $grupos = [
        [
            'label' => 'Gestión Web',
            'icono' => 'fa-globe',
            'items' => [
                ['slug' => 'servicios', 'label' => 'Servicios', 'icono' => 'fa-hand-holding-heart', 'ruta' => 'panel.info-publica.index', 'patron' => 'panel.info-publica.*', 'params' => ['tab' => 'servicios'], 'tab' => 'servicios'],
                ['slug' => 'especialidades', 'label' => 'Especialidades', 'icono' => 'fa-brain', 'ruta' => 'panel.info-publica.index', 'patron' => 'panel.info-publica.*', 'params' => ['tab' => 'especialidades'], 'tab' => 'especialidades'],
                ['slug' => 'faq', 'label' => 'Preguntas frecuentes', 'icono' => 'fa-circle-question', 'ruta' => 'panel.faq.index', 'patron' => 'panel.faq.*'],
                ['slug' => 'frases', 'label' => 'Frases públicas', 'icono' => 'fa-quote-left', 'ruta' => 'panel.frases.index', 'patron' => 'panel.frases.*'],
                ['slug' => 'redes', 'label' => 'Redes sociales', 'icono' => 'fa-share-nodes', 'ruta' => 'panel.redes.index', 'patron' => 'panel.redes.*'],
                ['slug' => 'imagenes', 'label' => 'Imágenes', 'icono' => 'fa-images', 'ruta' => 'panel.imagenes.index', 'patron' => 'panel.imagenes.*'],
                ['slug' => 'info-publica', 'label' => 'Información pública', 'icono' => 'fa-address-card', 'ruta' => 'panel.info-publica.index', 'patron' => 'panel.info-publica.*', 'tab' => 'perfil'],
            ],
        ],
        [
            'label' => 'Configuración',
            'icono' => 'fa-gear',
            'items' => [
                ['slug' => 'disponibilidad', 'label' => 'Disponibilidad', 'icono' => 'fa-clock', 'ruta' => 'panel.disponibilidad.index', 'patron' => 'panel.disponibilidad.*'],
                ['slug' => 'proteccion', 'label' => 'Protección de datos', 'icono' => 'fa-shield-halved', 'ruta' => 'panel.proteccion.index', 'patron' => 'panel.proteccion.*'],
                ['slug' => 'temas', 'label' => 'Temas visuales', 'icono' => 'fa-palette', 'ruta' => 'panel.temas.index', 'patron' => 'panel.temas.*'],
                ['slug' => 'secciones', 'label' => 'Secciones de la web', 'icono' => 'fa-toggle-on', 'ruta' => 'panel.secciones.index', 'patron' => 'panel.secciones.*'],
                ['slug' => 'notificaciones', 'label' => 'Email y notificaciones', 'icono' => 'fa-envelope', 'ruta' => 'panel.notificaciones.index', 'patron' => 'panel.notificaciones.*'],
            ],
        ],
    ];

    $urlItem = fn ($item) => isset($item['ruta']) ? route($item['ruta'], $item['params'] ?? []) : route('panel.proximamente', $item['slug']);

    $activoItem = function ($item) {
        if (isset($item['ruta'])) {
            if (! request()->routeIs($item['patron'] ?? $item['ruta'])) {
                return false;
            }
            if (isset($item['tab'])) {
                return request('tab', 'perfil') === $item['tab'];
            }
            return true;
        }
        return request()->routeIs('panel.proximamente') && request()->segment(3) === $item['slug'];
    };

    $grupoAbierto = function ($items) use ($activoItem) {
        foreach ($items as $it) {
            if ($activoItem($it)) {
                return true;
            }
        }
        return false;
    };
@endphp

<aside class="dash__sidebar" data-sidebar>
    <div class="side__brand">
        <span class="side__brand-badge"><i class="fa-solid fa-brain"></i></span>
        <div>
            <div class="side__brand-name">{{ config('psicocms.nombre') }}</div>
            <div class="side__brand-sub">Panel administrativo</div>
        </div>
    </div>

    <nav class="side__nav">
        @foreach ($directos as $item)
            <a href="{{ $urlItem($item) }}" class="side__link {{ $activoItem($item) ? 'is-active' : '' }}">
                <i class="fa-solid {{ $item['icono'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        @foreach ($grupos as $grupo)
            <div class="side__group {{ $grupoAbierto($grupo['items']) ? 'is-open' : '' }}" data-group>
                <button type="button" class="side__group-toggle" data-group-toggle>
                    <i class="fa-solid {{ $grupo['icono'] }}"></i>
                    <span>{{ $grupo['label'] }}</span>
                    <i class="fa-solid fa-chevron-down side__group-chevron"></i>
                </button>
                <div class="side__submenu">
                    <div class="side__submenu-inner">
                        @foreach ($grupo['items'] as $item)
                            <a href="{{ $urlItem($item) }}" class="side__sublink {{ $activoItem($item) ? 'is-active' : '' }}">
                                <i class="fa-solid {{ $item['icono'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </nav>

    <div class="side__foot">
        <a href="{{ url('/') }}" target="_blank" rel="noopener" class="side__foot-link">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Ver web pública</span>
        </a>
        <form method="POST" action="{{ route('acceso.logout') }}">
            @csrf
            <button type="submit" class="side__foot-link" style="width:100%;border:none;background:transparent;cursor:pointer;text-align:left;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>
