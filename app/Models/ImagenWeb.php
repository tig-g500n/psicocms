<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenWeb extends Model
{
    protected $table = 'imagenes_web';

    protected $guarded = [];

    public static function catalogo(): array
    {
        return config('imagenes.grupos', []);
    }

    public static function slots(): array
    {
        $slots = [];

        foreach (static::catalogo() as $grupo) {
            foreach ($grupo['items'] as $item) {
                $slots[$item['clave']] = $item;
            }
        }

        return $slots;
    }

    public static function defecto(string $clave): ?string
    {
        return static::slots()[$clave]['defecto'] ?? null;
    }

    public static function overrides(): array
    {
        return static::pluck('ruta', 'clave')->all();
    }

    public static function ruta(string $clave): ?string
    {
        $override = static::where('clave', $clave)->value('ruta');

        if ($override && is_file(public_path($override))) {
            return $override;
        }

        return static::defecto($clave);
    }

    public static function url(string $clave): ?string
    {
        $ruta = static::ruta($clave);

        return $ruta ? asset($ruta) : null;
    }
}
