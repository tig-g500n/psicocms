<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\PerfilPublico;
use App\Services\Notificador;
use App\Services\SlotService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    public function __construct(private SlotService $slots, private Notificador $notificador)
    {
    }

    public function dias(Request $request): JsonResponse
    {
        $request->validate([
            'modalidad' => ['required', Rule::in(['online', 'presencial'])],
            'desde' => ['required', 'date'],
            'hasta' => ['required', 'date'],
        ]);

        $desde = Carbon::parse($request->desde)->startOfDay();
        $hasta = Carbon::parse($request->hasta)->startOfDay();
        $hoy = Carbon::today();

        if ($desde->lt($hoy)) {
            $desde = $hoy->copy();
        }

        $dias = [];
        $cursor = $desde->copy();
        $limite = 0;

        while ($cursor->lte($hasta) && $limite < 120) {
            if (! empty($this->slots->huecosDisponibles($request->modalidad, $cursor->toDateString()))) {
                $dias[] = $cursor->toDateString();
            }
            $cursor->addDay();
            $limite++;
        }

        return response()->json(['dias' => $dias]);
    }

    public function horas(Request $request): JsonResponse
    {
        $request->validate([
            'modalidad' => ['required', Rule::in(['online', 'presencial'])],
            'fecha' => ['required', 'date'],
        ]);

        if (Carbon::parse($request->fecha)->lt(Carbon::today())) {
            return response()->json(['huecos' => []]);
        }

        $huecos = collect($this->slots->huecosDisponibles($request->modalidad, $request->fecha))
            ->map(fn ($inicio) => [
                'inicio' => $inicio,
                'fin' => $this->slots->finDesde($request->modalidad, $inicio),
            ])->all();

        return response()->json(['huecos' => $huecos]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(seccion_activa('reservas'), 404);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9\s]+$/'],
            'motivo' => ['nullable', 'string', 'max:1000'],
            'modalidad' => ['required', Rule::in(['online', 'presencial'])],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ], [
            'telefono.regex' => 'El teléfono solo puede contener números.',
        ]);

        $fechaCarbon = Carbon::parse($datos['fecha']);
        if ($fechaCarbon->lt(Carbon::today())) {
            return response()->json(['mensaje' => 'La fecha seleccionada no es válida.'], 422);
        }

        $horaFin = $this->slots->finDesde($datos['modalidad'], $datos['hora_inicio']);

        $disponibles = $this->slots->huecosDisponibles($datos['modalidad'], $datos['fecha']);
        if (! in_array($datos['hora_inicio'], $disponibles, true)) {
            return response()->json(['mensaje' => 'Ese horario ya no está disponible. Elige otro, por favor.'], 422);
        }

        $telefono = preg_replace('/\s+/', '', $datos['telefono']);

        $paciente = Paciente::firstOrNew(['telefono' => $telefono]);
        $paciente->nombre = $datos['nombre'];
        if (! $paciente->exists) {
            $paciente->estado = 'activo';
            $paciente->modalidad_pref = $datos['modalidad'];
            $paciente->motivo = $datos['motivo'] ?? null;
        }
        $paciente->save();

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $horaFin,
            'modalidad' => $datos['modalidad'],
            'estado' => 'pendiente',
            'origen' => 'publico',
            'notas' => $datos['motivo'] ?? null,
        ]);

        $this->avisarPorEmail($cita, $paciente);

        return response()->json([
            'ok' => true,
            'mensaje' => '¡Tu cita se ha reservado correctamente!',
            'cita' => [
                'fecha' => $fechaCarbon->format('d/m/Y'),
                'hora' => substr($datos['hora_inicio'], 0, 5),
                'modalidad' => ucfirst($datos['modalidad']),
            ],
            'google_url' => $this->googleCalendarUrl($cita),
        ]);
    }

    private function avisarPorEmail(Cita $cita, Paciente $paciente): void
    {
        try {
            $cuerpo = "Nueva cita reservada desde tu web:\n\n"
                ."Paciente: {$paciente->nombre}\n"
                ."Teléfono: {$paciente->telefono}\n"
                ."Fecha: ".Carbon::parse($cita->fecha)->format('d/m/Y')."\n"
                ."Hora: ".substr($cita->hora_inicio, 0, 5)."\n"
                ."Modalidad: ".ucfirst($cita->modalidad)."\n"
                .($cita->notas ? "Motivo: {$cita->notas}\n" : '');

            $this->notificador->enviarAviso('Nueva cita reservada · '.$paciente->nombre, $cuerpo);
        } catch (\Throwable $e) {
            // El fallo del email nunca debe impedir la reserva.
        }
    }

    private function googleCalendarUrl(Cita $cita): string
    {
        $perfil = PerfilPublico::first();
        $nombre = trim(($perfil->nombre ?? '').' '.($perfil->apellidos ?? '')) ?: config('psicocms.nombre');

        $inicio = Carbon::parse($cita->fecha->toDateString().' '.$cita->hora_inicio);
        $fin = Carbon::parse($cita->fecha->toDateString().' '.$cita->hora_fin);

        return 'https://calendar.google.com/calendar/render?'.http_build_query([
            'action' => 'TEMPLATE',
            'text' => 'Cita '.$cita->modalidad.' con '.$nombre,
            'dates' => $inicio->format('Ymd\THis').'/'.$fin->format('Ymd\THis'),
            'details' => 'Cita de psicología reservada desde la web.',
            'location' => $cita->modalidad === 'presencial' ? ($perfil->direccion ?? '') : 'Online',
        ]);
    }
}
