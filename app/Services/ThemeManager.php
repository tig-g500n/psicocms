<?php

namespace App\Services;

use App\Models\ConfiguracionWeb;
use Illuminate\Support\Str;

class ThemeManager
{
    public function todos(): array
    {
        $ruta = config('psicocms.themes_path');
        $temas = [];

        foreach (glob($ruta.'/*', GLOB_ONLYDIR) as $dir) {
            $manifiesto = $dir.'/theme.json';

            if (! is_file($manifiesto)) {
                continue;
            }

            $slug = basename($dir);
            $datos = json_decode(file_get_contents($manifiesto), true) ?: [];
            $temas[$slug] = $this->normalizar($slug, $datos);
        }

        $orden = config('psicocms.temas_disponibles', []);

        uksort($temas, function ($a, $b) use ($orden) {
            $pa = array_search($a, $orden, true);
            $pb = array_search($b, $orden, true);
            $pa = $pa === false ? PHP_INT_MAX : $pa;
            $pb = $pb === false ? PHP_INT_MAX : $pb;

            return $pa <=> $pb ?: strcmp($a, $b);
        });

        return $temas;
    }

    public function manifiesto(string $slug): ?array
    {
        return $this->todos()[$slug] ?? null;
    }

    public function existe(string $slug): bool
    {
        return isset($this->todos()[$slug]);
    }

    public function activo(): string
    {
        $slug = ConfiguracionWeb::obtener('tema_activo', config('psicocms.tema_por_defecto'));

        return $this->existe($slug) ? $slug : config('psicocms.tema_por_defecto');
    }

    public function modoActivo(): string
    {
        $modo = ConfiguracionWeb::obtener('modo', config('psicocms.modo_por_defecto'));

        return in_array($modo, config('psicocms.modos_disponibles'), true)
            ? $modo
            : config('psicocms.modo_por_defecto');
    }

    private function normalizar(string $slug, array $m): array
    {
        return [
            'slug' => $slug,
            'nombre' => $m['nombre'] ?? Str::title($slug),
            'descripcion' => $m['descripcion'] ?? '',
            'primario' => $m['primario'] ?? '#3b5a7a',
            'secundario' => $m['secundario'] ?? '#9fb4cc',
            'acento' => $m['acento'] ?? '#976147',
            'fondo' => $m['fondo'] ?? '#f6f1ee',
            'superficie' => $m['superficie'] ?? '#ffffff',
            'texto' => $m['texto'] ?? '#3a3a3a',
            'radio' => $m['radio'] ?? '1.4rem',
            'fuente' => $m['fuente'] ?? 'Lexend',
        ];
    }
}
