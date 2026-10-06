<?php

return [

    'nombre' => 'PsicoCMS',

    'version' => '0.1.0',

    'themes_path' => public_path('themes'),

    'themes_uri' => 'themes',

    'tema_por_defecto' => 'base',

    'modo_por_defecto' => 'multipagina',

    'temas_disponibles' => ['base', 'sereno', 'aurora', 'nube', 'bosque'],

    'modos_disponibles' => ['landing', 'multipagina'],

    'install_flag' => storage_path('installed'),

    'color_dashboard_por_defecto' => 'azul',

    'apariencia_dashboard_por_defecto' => 'claro',

    'colores_dashboard' => [
        ['clave' => 'azul', 'nombre' => 'Azul sereno', 'primary' => '#3b5a7a', 'dark' => '#2e4763'],
        ['clave' => 'salvia', 'nombre' => 'Verde salvia', 'primary' => '#5b8c6e', 'dark' => '#466f56'],
        ['clave' => 'turquesa', 'nombre' => 'Turquesa', 'primary' => '#2f8f9d', 'dark' => '#237580'],
        ['clave' => 'lavanda', 'nombre' => 'Lavanda', 'primary' => '#7c6a9c', 'dark' => '#63527f'],
        ['clave' => 'coral', 'nombre' => 'Coral', 'primary' => '#c96f53', 'dark' => '#a95740'],
        ['clave' => 'rosa', 'nombre' => 'Rosa palo', 'primary' => '#c46b89', 'dark' => '#a4536e'],
        ['clave' => 'ocre', 'nombre' => 'Ocre cálido', 'primary' => '#bf933a', 'dark' => '#9c7729'],
        ['clave' => 'noche', 'nombre' => 'Azul noche', 'primary' => '#46566b', 'dark' => '#333f4f'],
    ],

    'secciones_web' => [
        ['clave' => 'sobre_mi', 'label' => 'Sobre mí', 'descripcion' => 'Tu presentación y trayectoria.', 'icono' => 'fa-user', 'defecto' => true],
        ['clave' => 'servicios', 'label' => 'Servicios', 'descripcion' => 'Listado de servicios que ofreces.', 'icono' => 'fa-hand-holding-heart', 'defecto' => true],
        ['clave' => 'especialidades', 'label' => 'Especialidades', 'descripcion' => 'Áreas en las que trabajas.', 'icono' => 'fa-brain', 'defecto' => true],
        ['clave' => 'planes', 'label' => 'Planes y precios', 'descripcion' => 'Tarifas online y presenciales.', 'icono' => 'fa-tags', 'defecto' => true],
        ['clave' => 'blog', 'label' => 'Blog', 'descripcion' => 'Artículos y publicaciones.', 'icono' => 'fa-newspaper', 'defecto' => true],
        ['clave' => 'faq', 'label' => 'Preguntas frecuentes', 'descripcion' => 'Dudas habituales de pacientes.', 'icono' => 'fa-circle-question', 'defecto' => true],
        ['clave' => 'reservas', 'label' => 'Reserva de citas', 'descripcion' => 'Sistema público de reservas.', 'icono' => 'fa-calendar-check', 'defecto' => true],
    ],
];
