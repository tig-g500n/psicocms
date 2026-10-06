<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategoria extends Model
{
    protected $table = 'blog_categorias';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (BlogCategoria $categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = static::slugUnico($categoria->nombre, $categoria->id);
            }
        });
    }

    public static function slugUnico(string $texto, ?int $exceptId = null): string
    {
        $base = Str::slug($texto) ?: 'categoria';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function articulos(): HasMany
    {
        return $this->hasMany(BlogArticulo::class, 'categoria_id');
    }
}
