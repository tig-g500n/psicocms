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
        return static::query()
            ->get()
            ->mapWithKeys(fn (self $imagen) => $imagen->tieneContenido()
                ? [$imagen->clave => route('imagenes.web', $imagen->clave)]
                : [])
            ->all();
    }

    public static function ruta(string $clave): ?string
    {
        $override = static::where('clave', $clave)->first();

        if ($override?->tieneContenido()) {
            return $override->urlPersonalizada();
        }

        if ($override?->ruta && is_file(public_path($override->ruta))) {
            return $override->ruta;
        }

        return static::defecto($clave);
    }

    public static function url(string $clave): ?string
    {
        $ruta = static::ruta($clave);

        if (! $ruta) {
            return null;
        }

        return str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://')
            ? $ruta
            : asset($ruta);
    }

    public function tieneContenido(): bool
    {
        return filled($this->contenido_base64) && filled($this->mime);
    }

    public function urlPersonalizada(): ?string
    {
        return $this->tieneContenido() ? route('imagenes.web', $this->clave) : null;
    }

    public static function personalizadaUrl(string $clave): ?string
    {
        return static::where('clave', $clave)->first()?->urlPersonalizada();
    }
}
