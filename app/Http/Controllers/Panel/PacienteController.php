<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PacienteController extends Controller
{
    public function index(Request $request): View
    {
        $pacientes = Paciente::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(fn ($s) => $s->where('nombre', 'like', '%'.$request->q.'%')
                    ->orWhere('telefono', 'like', '%'.$request->q.'%'));
            })
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->when($request->filled('modalidad'), fn ($q) => $q->where('modalidad_pref', $request->modalidad))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $estadisticas = [
            'total' => Paciente::count(),
            'activos' => Paciente::where('estado', 'activo')->count(),
            'con_cita_hoy' => Cita::whereDate('fecha', now()->toDateString())->where('estado', '!=', 'cancelada')->distinct('paciente_id')->count('paciente_id'),
        ];

        return view('panel.pacientes.index', [
            'pacientes' => $pacientes,
            'estadisticas' => $estadisticas,
            'filtros' => $request->only(['q', 'estado', 'modalidad']),
        ]);
    }

    public function create(): View
    {
        return view('panel.pacientes.form', [
            'paciente' => new Paciente(['estado' => 'activo']),
            'modo' => 'crear',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        Paciente::create($datos);

        return redirect()->route('panel.pacientes.index')->with('exito', 'Paciente creado correctamente.');
    }

    public function show(Paciente $paciente): View
    {
        return view('panel.pacientes.show', [
            'paciente' => $paciente,
            'citas' => $paciente->citas()->orderByDesc('fecha')->orderByDesc('hora_inicio')->paginate(10),
        ]);
    }

    public function edit(Paciente $paciente): View
    {
        return view('panel.pacientes.form', [
            'paciente' => $paciente,
            'modo' => 'editar',
        ]);
    }

    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $datos = $this->validar($request, $paciente);

        $paciente->update($datos);

        return redirect()->route('panel.pacientes.show', $paciente)->with('exito', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente): RedirectResponse
    {
        $paciente->delete();

        return redirect()->route('panel.pacientes.index')->with('exito', 'Paciente eliminado correctamente.');
    }

    public function buscar(Request $request): JsonResponse
    {
        $termino = trim((string) $request->query('q'));

        if (mb_strlen($termino) < 2) {
            return response()->json(['pacientes' => []]);
        }

        $pacientes = Paciente::where('nombre', 'like', '%'.$termino.'%')
            ->orWhere('telefono', 'like', '%'.$termino.'%')
            ->orderBy('nombre')
            ->limit(8)
            ->get(['id', 'nombre', 'telefono', 'email', 'modalidad_pref']);

        return response()->json(['pacientes' => $pacientes]);
    }

    private function validar(Request $request, ?Paciente $paciente = null): array
    {
        $request->merge(['telefono' => Paciente::normalizarTelefono($request->telefono)]);

        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30', Rule::unique('pacientes', 'telefono')->ignore($paciente?->id)],
            'email' => ['nullable', 'email', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'genero' => ['nullable', 'string', 'max:30'],
            'modalidad_pref' => ['nullable', Rule::in(['online', 'presencial'])],
            'estado' => ['required', Rule::in(['activo', 'pausado', 'inactivo'])],
            'motivo' => ['nullable', 'string', 'max:1000'],
            'notas' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'telefono' => 'teléfono',
        ]);
    }
}
