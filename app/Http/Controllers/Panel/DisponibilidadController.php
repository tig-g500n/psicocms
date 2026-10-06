<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionWeb;
use App\Models\DisponibilidadSlot;
use App\Models\PeriodoVacaciones;
use App\Services\SlotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DisponibilidadController extends Controller
{
    public function __construct(private readonly SlotService $slots)
    {
    }

    public function index(): View
    {
        return view('panel.disponibilidad.index', [
            'configOnline' => $this->slots->config('online'),
            'configPresencial' => $this->slots->config('presencial'),
            'gridOnline' => $this->slots->gridSemana('online'),
            'gridPresencial' => $this->slots->gridSemana('presencial'),
            'modoVacaciones' => $this->slots->modoVacaciones(),
            'periodos' => PeriodoVacaciones::orderBy('fecha_inicio')->get(),
            'dias' => SlotService::DIAS,
        ]);
    }

    public function guardar(Request $request, string $modalidad): RedirectResponse
    {
        abort_unless(in_array($modalidad, ['online', 'presencial'], true), 404);

        $datos = $request->validate([
            'duracion_min' => ['required', 'integer', 'min:5', 'max:600'],
            'descanso_min' => ['required', 'integer', 'min:0', 'max:240'],
            'descanso_activo' => ['nullable', 'boolean'],
            'hora_entrada' => ['required', 'date_format:H:i'],
            'hora_salida' => ['required', 'date_format:H:i', 'after:hora_entrada'],
            'slots' => ['nullable', 'array'],
            'slots.*' => ['string'],
        ]);

        $config = $this->slots->config($modalidad);
        $config->update([
            'duracion_min' => $datos['duracion_min'],
            'descanso_min' => $datos['descanso_min'],
            'descanso_activo' => $request->boolean('descanso_activo'),
            'hora_entrada' => $datos['hora_entrada'],
            'hora_salida' => $datos['hora_salida'],
        ]);

        DisponibilidadSlot::where('modalidad', $modalidad)->delete();

        $filas = [];
        foreach ($datos['slots'] ?? [] as $slot) {
            [$dia, $hora] = array_pad(explode('|', $slot), 2, null);
            if ($dia === null || $hora === null) {
                continue;
            }
            $filas[] = [
                'modalidad' => $modalidad,
                'dia_semana' => (int) $dia,
                'hora_inicio' => $hora,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($filas !== []) {
            DisponibilidadSlot::insert($filas);
        }

        return back()->with('exito', 'Disponibilidad '.$modalidad.' guardada correctamente.');
    }

    public function modoVacaciones(Request $request): RedirectResponse
    {
        ConfiguracionWeb::guardar('modo_vacaciones', $request->boolean('modo_vacaciones') ? '1' : '0');

        return back()->with('exito', 'Modo vacaciones actualizado.');
    }

    public function agregarVacaciones(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        PeriodoVacaciones::create($datos);

        return back()->with('exito', 'Periodo de vacaciones añadido.');
    }

    public function eliminarVacaciones(PeriodoVacaciones $periodo): RedirectResponse
    {
        $periodo->delete();

        return back()->with('exito', 'Periodo de vacaciones eliminado.');
    }
}
