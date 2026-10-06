<?php

namespace App\Services;

use App\Models\PerfilPublico;
use App\Models\Paciente;
use App\Models\Psicologa;
use Illuminate\Support\Facades\Auth;

class ProteccionDatosService
{
    public function placeholders(): array
    {
        return [
            '{{paciente_nombre}}' => 'Nombre del paciente',
            '{{paciente_telefono}}' => 'Teléfono',
            '{{paciente_email}}' => 'Email',
            '{{paciente_fecha_nacimiento}}' => 'Fecha de nacimiento',
            '{{paciente_direccion}}' => 'Dirección',
            '{{psicologa_nombre}}' => 'Nombre de la profesional',
            '{{psicologa_colegiado}}' => 'Nº de colegiado/a',
            '{{fecha}}' => 'Fecha actual',
        ];
    }

    public function reemplazar(string $html, ?Paciente $paciente = null): string
    {
        $psicologa = Auth::user() ?? Psicologa::first();
        $perfil = PerfilPublico::first();
        $blanco = '____________';

        $valores = [
            '{{paciente_nombre}}' => $paciente?->nombre ?: $blanco,
            '{{paciente_telefono}}' => $paciente?->telefono ?: $blanco,
            '{{paciente_email}}' => $paciente?->email ?: $blanco,
            '{{paciente_fecha_nacimiento}}' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?: $blanco,
            '{{paciente_direccion}}' => $paciente?->direccion ?: $blanco,
            '{{psicologa_nombre}}' => trim(($psicologa->nombre ?? '').' '.($psicologa->apellidos ?? '')) ?: $blanco,
            '{{psicologa_colegiado}}' => $perfil?->num_colegiado ?: $blanco,
            '{{fecha}}' => now()->format('d/m/Y'),
        ];

        return strtr($html, $valores);
    }
}
