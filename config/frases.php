<?php

return [

    'grupos' => [
        [
            'label' => 'Generales',
            'icono' => 'fa-quote-left',
            'items' => [
                ['clave' => 'boton_cita', 'label' => 'Botón «Pedir cita»', 'defecto' => 'Pedir cita', 'largo' => false],
                ['clave' => 'boton_contacto', 'label' => 'Botón «Contactar»', 'defecto' => 'Contactar', 'largo' => false],
                ['clave' => 'cta_principal', 'label' => 'Llamada a la acción principal', 'defecto' => 'Da el primer paso hacia tu bienestar', 'largo' => false],
            ],
        ],
        [
            'label' => 'Inicio',
            'icono' => 'fa-house',
            'items' => [
                ['clave' => 'hero_titulo', 'label' => 'Título principal', 'defecto' => 'Tu bienestar emocional es el primer paso', 'largo' => false],
                ['clave' => 'hero_subtitulo', 'label' => 'Subtítulo', 'defecto' => 'Acompañamiento psicológico cercano, profesional y adaptado a ti.', 'largo' => true],
            ],
        ],
        [
            'label' => 'Servicios',
            'icono' => 'fa-hand-holding-heart',
            'items' => [
                ['clave' => 'servicios_titulo', 'label' => 'Título de la sección', 'defecto' => 'Cómo puedo ayudarte', 'largo' => false],
                ['clave' => 'servicios_subtitulo', 'label' => 'Texto introductorio', 'defecto' => 'Formas de acompañarte adaptadas a lo que necesitas en cada momento.', 'largo' => true],
            ],
        ],
        [
            'label' => 'Reservas',
            'icono' => 'fa-calendar-check',
            'items' => [
                ['clave' => 'reservas_titulo', 'label' => 'Título de la sección', 'defecto' => 'Pide tu cita', 'largo' => false],
                ['clave' => 'reservas_texto', 'label' => 'Texto introductorio', 'defecto' => 'Elige la modalidad y el horario que mejor te venga. Es rápido y sencillo.', 'largo' => true],
            ],
        ],
        [
            'label' => 'Contacto y footer',
            'icono' => 'fa-envelope',
            'items' => [
                ['clave' => 'contacto_titulo', 'label' => 'Título de contacto', 'defecto' => '¿Hablamos?', 'largo' => false],
                ['clave' => 'footer_texto', 'label' => 'Texto del footer', 'defecto' => 'Psicología para acompañarte en tu proceso de bienestar.', 'largo' => true],
            ],
        ],
    ],
];
