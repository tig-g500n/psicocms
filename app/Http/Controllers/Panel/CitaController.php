<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Services\SlotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CitaController extends Controller
{
    public function __construct(private readonly SlotService $slots)
    {
    }

    public function index(Request $request): View
    {
        $citas = Cita::with('paciente')
            ->when($request->filled('modalidad'), fn ($q) => $q->where('modalidad', $request->modalidad))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->when($request->filled('fecha'), fn ($q) => $q->whereDate('fecha', $request->fecha))
            ->when($request->filled('q'), function ($q) use ($request) {
                $termino = $request->q;
                $q->whereHas('paciente', function ($p) use ($termino) {
                    $p->where('nombre', 'like', "%{$termino}%")
                        ->orWhere('telefono', 'like', "%{$termino}%");
                });
            })
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->paginate(10)
            ->withQueryString();

        return view('panel.citas.index', [
            'citas' => $citas,
            'filtros' => $request->only(['q', 'modalidad', 'estado', 'fecha']),
        ]);
    }

    public function create(Request $request): View
    {
        $modalidad = in_array($request->query('modalidad'), ['online', 'presencial'], true)
            ? $request->query('modalidad')
            : 'online';

        $cita = new Cita(['modalidad' => $modalidad, 'estado' => 'pendiente']);

        if ($request->filled('fecha')) {
            try {
                $cita->fecha = \Carbon\Carbon::parse($request->query('fecha'));
            } catch (\Throwable $e) {
                // fecha inválida: se ignora
            }
        }

        $paciente = $request->filled('telefono')
            ? Paciente::where('telefono', Paciente::normalizarTelefono($request->query('telefono')))->first()
            : null;

        return view('panel.citas.form', [
            'cita' => $cita,
            'paciente' => $paciente,
            'modo' => 'crear',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarCita($request);

        $telefono = preg_replace('/\s+/', '', $datos['paciente_telefono']);
        $horaFin = $this->slots->finDesde($datos['modalidad'], $datos['hora_inicio']);

        $this->comprobarDisponibilidad($datos, $horaFin);

        $paciente = Paciente::updateOrCreate(
            ['telefono' => $telefono],
            ['nombre' => $datos['paciente_nombre']]
        );

        Cita::create([
            'paciente_id' => $paciente->id,
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $horaFin,
            'modalidad' => $datos['modalidad'],
            'estado' => $datos['estado'],
            'origen' => 'manual',
            'notas' => $datos['notas'] ?? null,
        ]);

        return redirect()->route('panel.citas.index')->with('exito', 'Cita creada correctamente.');
    }

    public function edit(Cita $cita): View
    {
        return view('panel.citas.form', [
            'cita' => $cita,
            'paciente' => $cita->paciente,
            'modo' => 'editar',
        ]);
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $datos = $this->validarCita($request);

        $telefono = preg_replace('/\s+/', '', $datos['paciente_telefono']);
        $horaFin = $this->slots->finDesde($datos['modalidad'], $datos['hora_inicio']);

        $this->comprobarDisponibilidad($datos, $horaFin, $cita->id);

        $paciente = Paciente::updateOrCreate(
            ['telefono' => $telefono],
            ['nombre' => $datos['paciente_nombre']]
        );

        $cita->update([
            'paciente_id' => $paciente->id,
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $horaFin,
            'modalidad' => $datos['modalidad'],
            'estado' => $datos['estado'],
            'notas' => $datos['notas'] ?? null,
        ]);

        return redirect()->route('panel.citas.index')->with('exito', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();

        return back()->with('exito', 'Cita eliminada correctamente.');
    }

    public function huecos(Request $request): JsonResponse
    {
        $request->validate([
            'modalidad' => ['required', Rule::in(['online', 'presencial'])],
            'fecha' => ['required', 'date'],
        ]);

        $huecos = $this->slots->huecosDisponibles(
            $request->modalidad,
            $request->fecha,
            $request->filled('except') ? (int) $request->except : null
        );

        $motivo = null;
        if ($this->slots->modoVacaciones()) {
            $motivo = 'modo';
        } elseif ($this->slots->enVacaciones(\Carbon\Carbon::parse($request->fecha))) {
            $motivo = 'periodo';
        }

        return response()->json([
            'huecos' => $huecos,
            'bloqueada' => $motivo !== null,
            'motivo' => $motivo,
        ]);
    }

    private function validarCita(Request $request): array
    {
        return $request->validate([
            'paciente_nombre' => ['required', 'string', 'max:255'],
            'paciente_telefono' => ['required', 'string', 'max:30'],
            'modalidad' => ['required', Rule::in(['online', 'presencial'])],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'estado' => ['required', Rule::in(['pendiente', 'confirmada', 'cancelada', 'realizada'])],
            'notas' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function comprobarDisponibilidad(array $datos, string $horaFin, ?int $exceptId = null): void
    {
        if ($this->slots->haySolapamiento($datos['modalidad'], $datos['fecha'], $datos['hora_inicio'], $horaFin, $exceptId)) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'Ya existe una cita que se solapa con ese horario.',
            ]);
        }

        if ($datos['estado'] !== 'cancelada') {
            $disponibles = $this->slots->huecosDisponibles($datos['modalidad'], $datos['fecha'], $exceptId);
            if (! in_array($datos['hora_inicio'], $disponibles, true)) {
                throw ValidationException::withMessages([
                    'hora_inicio' => 'Ese horario no está disponible según tu configuración (revisa disponibilidad, descansos y vacaciones).',
                ]);
            }
        }
    }
}
