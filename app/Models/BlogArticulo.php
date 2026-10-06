<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogArticulo extends Model
{
    protected $table = 'blog_articulos';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'publicado' => 'boolean',
            'fecha' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogArticulo $articulo) {
            if (empty($articulo->slug)) {
                $articulo->slug = static::slugUnico($articulo->titulo, $articulo->id);
            }
        });
    }

    public static function slugUnico(string $texto, ?int $exceptId = null): string
    {
        $base = Str::slug($texto) ?: 'articulo';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(BlogCategoria::class, 'categoria_id');
    }
}
