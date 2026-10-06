<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriaAdjunto extends Model
{
    protected $table = 'historia_adjuntos';

    protected $guarded = [];

    public function entrada(): BelongsTo
    {
        return $this->belongsTo(HistoriaEntrada::class, 'entrada_id');
    }

    public function esImagen(): bool
    {
        return $this->tipo === 'imagen';
    }
}
