<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistoriaEntrada extends Model
{
    protected $table = 'historia_entradas';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function adjuntos(): HasMany
    {
        return $this->hasMany(HistoriaAdjunto::class, 'entrada_id');
    }
}
