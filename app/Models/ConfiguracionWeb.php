<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionWeb extends Model
{
    protected $table = 'configuracion_web';

    protected $guarded = [];

    public static function obtener(string $clave, $defecto = null)
    {
        return static::where('clave', $clave)->value('valor') ?? $defecto;
    }

    public static function guardar(string $clave, $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
    }
}
