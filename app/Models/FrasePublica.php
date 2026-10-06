<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrasePublica extends Model
{
    protected $table = 'frases_publicas';

    protected $guarded = [];

    public static function catalogo(): array
    {
        return config('frases.grupos', []);
    }

    public static function items(): array
    {
        $items = [];

        foreach (static::catalogo() as $grupo) {
            foreach ($grupo['items'] as $item) {
                $items[$item['clave']] = $item;
            }
        }

        return $items;
    }

    public static function defecto(string $clave): ?string
    {
        return static::items()[$clave]['defecto'] ?? null;
    }

    public static function overrides(): array
    {
        return static::pluck('texto', 'clave')->all();
    }

    public static function texto(string $clave): ?string
    {
        $override = static::where('clave', $clave)->value('texto');

        return ($override !== null && $override !== '') ? $override : static::defecto($clave);
    }
}
