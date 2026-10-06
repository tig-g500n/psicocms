<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisponibilidadConfig extends Model
{
    protected $table = 'disponibilidad_config';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'descanso_activo' => 'boolean',
        ];
    }

    public function pasoMinutos(): int
    {
        return $this->duracion_min + ($this->descanso_activo ? $this->descanso_min : 0);
    }
}
