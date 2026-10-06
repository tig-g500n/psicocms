<?php

use App\Models\ConfiguracionWeb;
use App\Models\FrasePublica;
use App\Models\ImagenWeb;

if (! function_exists('imagen_web')) {
    function imagen_web(string $clave): ?string
    {
        return ImagenWeb::url($clave);
    }
}

if (! function_exists('frase_publica')) {
    function frase_publica(string $clave): ?string
    {
        return FrasePublica::texto($clave);
    }
}

if (! function_exists('lista_caracteristicas')) {
    function lista_caracteristicas(?string $texto): array
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return [];
        }

        $lineas = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $texto))));

        if (count($lineas) > 1) {
            return $lineas;
        }

        $partes = array_values(array_filter(array_map('trim', preg_split('/[;,•|]/u', $texto))));

        return count($partes) ? $partes : [$texto];
    }
}

if (! function_exists('precio_mxn')) {
    function precio_mxn(?string $valor): ?string
    {
        $valor = trim((string) $valor);

        if ($valor === '') {
            return null;
        }

        $numero = str_replace([',', ' '], '', $valor);

        if (is_numeric($numero)) {
            $numero = (float) $numero;
            $formateado = $numero == (int) $numero
                ? number_format($numero, 0, '.', ',')
                : number_format($numero, 2, '.', ',');

            return '$'.$formateado.' MXN';
        }

        return $valor;
    }
}

if (! function_exists('red_social')) {
    function red_social(string $clave): ?string
    {
        $url = ConfiguracionWeb::obtener('red_'.$clave);

        return $url !== null && $url !== '' ? $url : null;
    }
}

if (! function_exists('redes_configuradas')) {
    function redes_configuradas(): array
    {
        $activas = [];

        foreach (config('redes.redes', []) as $red) {
            $url = red_social($red['clave']);
            if ($url) {
                $red['url'] = $url;
                $activas[] = $red;
            }
        }

        return $activas;
    }
}

if (! function_exists('dashboard_apariencia')) {
    function dashboard_apariencia(): string
    {
        $valor = ConfiguracionWeb::obtener('dashboard_apariencia', config('psicocms.apariencia_dashboard_por_defecto'));

        return in_array($valor, ['claro', 'oscuro'], true) ? $valor : 'claro';
    }
}

if (! function_exists('dashboard_color')) {
    function dashboard_color(): array
    {
        $colores = config('psicocms.colores_dashboard', []);
        $clave = ConfiguracionWeb::obtener('dashboard_color', config('psicocms.color_dashboard_por_defecto'));

        foreach ($colores as $color) {
            if ($color['clave'] === $clave) {
                return $color;
            }
        }

        return $colores[0] ?? ['clave' => 'azul', 'nombre' => 'Azul', 'primary' => '#3b5a7a', 'dark' => '#2e4763'];
    }
}

if (! function_exists('seccion_activa')) {
    function seccion_activa(string $clave): bool
    {
        $defecto = '1';

        foreach (config('psicocms.secciones_web', []) as $seccion) {
            if ($seccion['clave'] === $clave) {
                $defecto = ($seccion['defecto'] ?? true) ? '1' : '0';
                break;
            }
        }

        return ConfiguracionWeb::obtener('seccion_'.$clave, $defecto) === '1';
    }
}
