<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoVacaciones extends Model
{
    protected $table = 'periodos_vacaciones';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }
}
