<?php

namespace Database\Seeders;

use App\Models\BlogCategoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Ansiedad y estrés',
            'Depresión',
            'Autoestima',
            'Relaciones de pareja',
            'Crecimiento personal',
            'Mindfulness y bienestar',
            'Terapia infantil y familiar',
            'Consejos y recursos',
        ];

        foreach ($categorias as $nombre) {
            BlogCategoria::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                ['nombre' => $nombre]
            );
        }
    }
}
