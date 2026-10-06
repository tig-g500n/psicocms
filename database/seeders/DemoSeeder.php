<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\DisponibilidadSlot;
use App\Models\Paciente;
use App\Services\SlotService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $slots = app(SlotService::class);

        foreach (['online', 'presencial'] as $modalidad) {
            $config = $slots->config($modalidad);
            $config->update(['hora_entrada' => '09:00', 'hora_salida' => '18:00', 'duracion_min' => 50, 'descanso_min' => 10, 'descanso_activo' => true]);

            DisponibilidadSlot::where('modalidad', $modalidad)->delete();
            $horas = $slots->horasCandidatas($modalidad);
            $filas = [];
            foreach (range(0, 4) as $dia) {
                foreach ($horas as $hora) {
                    $filas[] = ['modalidad' => $modalidad, 'dia_semana' => $dia, 'hora_inicio' => $hora, 'created_at' => now(), 'updated_at' => now()];
                }
            }
            DisponibilidadSlot::insert($filas);
        }

        $pacientes = [
            ['nombre' => 'Elena Martínez', 'telefono' => '600111222', 'modalidad_pref' => 'online'],
            ['nombre' => 'Carlos Valdés', 'telefono' => '600333444', 'modalidad_pref' => 'presencial'],
            ['nombre' => 'Sofía Ríos', 'telefono' => '600555666', 'modalidad_pref' => 'online'],
            ['nombre' => 'Miguel Rojas', 'telefono' => '600777888', 'modalidad_pref' => 'presencial'],
            ['nombre' => 'Laura Sánchez', 'telefono' => '600999000', 'modalidad_pref' => 'online'],
        ];

        foreach ($pacientes as $p) {
            $paciente = Paciente::updateOrCreate(['telefono' => $p['telefono']], $p + ['estado' => 'activo']);

            $fecha = Carbon::today()->addDays(rand(0, 10));
            while ($fecha->isWeekend()) {
                $fecha->addDay();
            }

            $modalidad = $p['modalidad_pref'];
            $huecos = $slots->huecosDisponibles($modalidad, $fecha->toDateString());
            if ($huecos === []) {
                continue;
            }
            $hora = $huecos[array_rand($huecos)];

            Cita::create([
                'paciente_id' => $paciente->id,
                'fecha' => $fecha->toDateString(),
                'hora_inicio' => $hora,
                'hora_fin' => $slots->finDesde($modalidad, $hora),
                'modalidad' => $modalidad,
                'estado' => ['pendiente', 'confirmada'][rand(0, 1)],
                'origen' => 'manual',
            ]);
        }
    }
}
