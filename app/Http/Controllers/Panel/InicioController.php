<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Services\SlotService;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __construct(private readonly SlotService $slots)
    {
    }

    public function index(): View
    {
        $hoy = now()->toDateString();

        $estadisticas = [
            'citas_hoy' => Cita::whereDate('fecha', $hoy)->where('estado', '!=', 'cancelada')->count(),
            'pacientes' => Paciente::where('estado', 'activo')->count(),
            'citas_mes' => Cita::whereYear('fecha', now()->year)->whereMonth('fecha', now()->month)->where('estado', '!=', 'cancelada')->count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->whereDate('fecha', '>=', $hoy)->count(),
        ];

        $proximas = Cita::with('paciente')
            ->whereDate('fecha', '>=', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(6)
            ->get();

        return view('panel.inicio', [
            'estadisticas' => $estadisticas,
            'proximas' => $proximas,
            'gridOnline' => $this->slots->gridSemana('online'),
            'configOnline' => $this->slots->config('online'),
            'dias' => SlotService::DIAS,
            'modoVacaciones' => $this->slots->modoVacaciones(),
        ]);
    }
}
