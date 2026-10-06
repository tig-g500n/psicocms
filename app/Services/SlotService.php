<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\ConfiguracionWeb;
use App\Models\DisponibilidadConfig;
use App\Models\DisponibilidadSlot;
use App\Models\PeriodoVacaciones;
use Carbon\Carbon;

class SlotService
{
    public const DIAS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    public function config(string $modalidad): DisponibilidadConfig
    {
        return DisponibilidadConfig::firstOrCreate(
            ['modalidad' => $modalidad],
            [
                'duracion_min' => 50,
                'descanso_min' => 10,
                'descanso_activo' => false,
                'hora_entrada' => '09:00',
                'hora_salida' => '18:00',
            ]
        );
    }

    public function paso(string $modalidad): int
    {
        return $this->config($modalidad)->pasoMinutos();
    }

    public function horasCandidatas(string $modalidad): array
    {
        $config = $this->config($modalidad);
        $entrada = $this->minutos($config->hora_entrada);
        $salida = $this->minutos($config->hora_salida);
        $paso = max(1, $config->pasoMinutos());
        $duracion = $config->duracion_min;

        $horas = [];
        for ($t = $entrada; $t + $duracion <= $salida; $t += $paso) {
            $horas[] = $this->hhmm($t);
        }

        return $horas;
    }

    public function gridSemana(string $modalidad): array
    {
        $candidatas = $this->horasCandidatas($modalidad);
        $guardados = DisponibilidadSlot::where('modalidad', $modalidad)
            ->get()
            ->groupBy('dia_semana')
            ->map(fn ($slots) => $slots->map(fn ($s) => substr($s->hora_inicio, 0, 5))->all())
            ->all();

        $grid = [];
        foreach (self::DIAS as $dia => $nombre) {
            $marcados = $guardados[$dia] ?? [];
            $grid[$dia] = [
                'nombre' => $nombre,
                'horas' => array_map(fn ($h) => [
                    'hora' => $h,
                    'marcado' => in_array($h, $marcados, true),
                ], $candidatas),
            ];
        }

        return $grid;
    }

    public function modoVacaciones(): bool
    {
        return (bool) ConfiguracionWeb::obtener('modo_vacaciones', '0');
    }

    public function enVacaciones(Carbon $fecha): bool
    {
        return PeriodoVacaciones::whereDate('fecha_inicio', '<=', $fecha->toDateString())
            ->whereDate('fecha_fin', '>=', $fecha->toDateString())
            ->exists();
    }

    public function huecosDisponibles(string $modalidad, string $fecha, ?int $exceptCitaId = null): array
    {
        if ($this->modoVacaciones()) {
            return [];
        }

        $f = Carbon::parse($fecha);
        if ($this->enVacaciones($f)) {
            return [];
        }

        $dia = ($f->dayOfWeek + 6) % 7;

        $marcados = DisponibilidadSlot::where('modalidad', $modalidad)
            ->where('dia_semana', $dia)
            ->pluck('hora_inicio')
            ->map(fn ($h) => substr($h, 0, 5))
            ->all();

        $candidatas = array_values(array_intersect($this->horasCandidatas($modalidad), $marcados));
        $duracion = $this->config($modalidad)->duracion_min;

        $ocupadas = Cita::where('modalidad', $modalidad)
            ->whereDate('fecha', $f->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->when($exceptCitaId, fn ($q) => $q->where('id', '!=', $exceptCitaId))
            ->get(['hora_inicio', 'hora_fin']);

        return array_values(array_filter($candidatas, function ($hora) use ($duracion, $ocupadas) {
            $ini = $this->minutos($hora);
            $fin = $ini + $duracion;
            foreach ($ocupadas as $cita) {
                $ci = $this->minutos($cita->hora_inicio);
                $cf = $this->minutos($cita->hora_fin);
                if ($ini < $cf && $ci < $fin) {
                    return false;
                }
            }
            return true;
        }));
    }

    public function haySolapamiento(string $modalidad, string $fecha, string $horaInicio, string $horaFin, ?int $exceptId = null): bool
    {
        $ini = $this->minutos($horaInicio);
        $fin = $this->minutos($horaFin);

        return Cita::where('modalidad', $modalidad)
            ->whereDate('fecha', Carbon::parse($fecha)->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->get(['hora_inicio', 'hora_fin'])
            ->contains(function ($cita) use ($ini, $fin) {
                $ci = $this->minutos($cita->hora_inicio);
                $cf = $this->minutos($cita->hora_fin);
                return $ini < $cf && $ci < $fin;
            });
    }

    public function finDesde(string $modalidad, string $horaInicio): string
    {
        return $this->hhmm($this->minutos($horaInicio) + $this->config($modalidad)->duracion_min);
    }

    public function fechaBloqueada(string $fecha): bool
    {
        return $this->modoVacaciones() || $this->enVacaciones(Carbon::parse($fecha));
    }

    private function minutos(string $hhmm): int
    {
        [$h, $m] = array_pad(explode(':', $hhmm), 2, 0);

        return ((int) $h) * 60 + (int) $m;
    }

    private function hhmm(int $minutos): string
    {
        return sprintf('%02d:%02d', intdiv($minutos, 60), $minutos % 60);
    }
}
