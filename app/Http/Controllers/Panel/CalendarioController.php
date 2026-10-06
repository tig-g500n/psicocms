<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarioController extends Controller
{
    public function index(): View
    {
        return view('panel.calendario.index');
    }

    public function eventos(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'desde' => ['required', 'date'],
            'hasta' => ['required', 'date', 'after_or_equal:desde'],
        ]);

        $citas = Cita::with('paciente')
            ->whereBetween('fecha', [$datos['desde'], $datos['hasta']])
            ->orderBy('hora_inicio')
            ->get()
            ->map(fn (Cita $cita) => [
                'id' => $cita->id,
                'fecha' => $cita->fecha->toDateString(),
                'hora' => $cita->hora_inicio_corta,
                'hora_fin' => $cita->hora_fin_corta,
                'modalidad' => $cita->modalidad,
                'estado' => $cita->estado,
                'paciente' => $cita->paciente->nombre ?? 'Paciente',
                'telefono' => $cita->paciente->telefono ?? '',
                'url' => route('panel.citas.edit', $cita),
            ]);

        return response()->json(['eventos' => $citas]);
    }
}
