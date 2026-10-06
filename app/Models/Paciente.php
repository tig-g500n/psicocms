<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    protected function telefono(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => preg_replace('/\s+/', '', (string) $value),
        );
    }

    public static function normalizarTelefono(?string $valor): string
    {
        return preg_replace('/\s+/', '', (string) $valor);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function entradas(): HasMany
    {
        return $this->hasMany(HistoriaEntrada::class);
    }

    public function ultimaCita(): ?Cita
    {
        return $this->citas()
            ->where('estado', '!=', 'cancelada')
            ->whereDate('fecha', '<=', now()->toDateString())
            ->orderByDesc('fecha')->orderByDesc('hora_inicio')
            ->first();
    }

    public function proximaCita(): ?Cita
    {
        return $this->citas()
            ->where('estado', '!=', 'cancelada')
            ->whereDate('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')->orderBy('hora_inicio')
            ->first();
    }
}
