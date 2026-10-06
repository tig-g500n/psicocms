<?php

return [

    'directorio_subidas' => 'uploads/web',

    'grupos' => [
        [
            'label' => 'Identidad',
            'icono' => 'fa-fingerprint',
            'items' => [
                ['clave' => 'logo', 'label' => 'Logo del sitio', 'descripcion' => 'Aparece en la cabecera y el footer.', 'defecto' => 'assets/img/stock/logo.png'],
            ],
        ],
        [
            'label' => 'Inicio',
            'icono' => 'fa-house',
            'items' => [
                ['clave' => 'hero', 'label' => 'Imagen principal', 'descripcion' => 'Fotografía destacada de la portada.', 'defecto' => 'assets/img/stock/psicologa.png'],
                ['clave' => 'hero_fondo', 'label' => 'Fondo de cabecera', 'descripcion' => 'Imagen de fondo de la zona superior.', 'defecto' => 'assets/img/stock/background-nav.jpg'],
            ],
        ],
        [
            'label' => 'Sobre mí',
            'icono' => 'fa-user',
            'items' => [
                ['clave' => 'sobre_mi', 'label' => 'Imagen «Sobre mí»', 'descripcion' => 'Acompaña tu presentación.', 'defecto' => 'assets/img/stock/why.jpg'],
                ['clave' => 'experiencia', 'label' => 'Imagen de trayectoria', 'descripcion' => 'Bloque de experiencia o formación.', 'defecto' => 'assets/img/stock/exp1.jpg'],
            ],
        ],
        [
            'label' => 'Servicios y especialidades',
            'icono' => 'fa-hand-holding-heart',
            'items' => [
                ['clave' => 'servicios_fondo', 'label' => 'Fondo de servicios', 'descripcion' => 'Fondo de la sección de servicios.', 'defecto' => 'assets/img/stock/bg-services.png'],
                ['clave' => 'especialidades', 'label' => 'Imagen de especialidades', 'descripcion' => 'Acompaña tus especialidades.', 'defecto' => 'assets/img/stock/psicologia.jpg'],
            ],
        ],
        [
            'label' => 'Reservas y contacto',
            'icono' => 'fa-calendar-check',
            'items' => [
                ['clave' => 'cita', 'label' => 'Imagen «Pide cita»', 'descripcion' => 'Acompaña la sección de reservas.', 'defecto' => 'assets/img/stock/terapia1.jpg'],
                ['clave' => 'contacto_fondo', 'label' => 'Fondo de contacto', 'descripcion' => 'Fondo de la zona de contacto.', 'defecto' => 'assets/img/stock/bg-contactnow.png'],
            ],
        ],
    ],
];
