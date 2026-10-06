<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\HistoriaAdjunto;
use App\Models\HistoriaEntrada;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoriaController extends Controller
{
    public function indice(): View
    {
        $pacientes = Paciente::whereHas('entradas')
            ->withCount('entradas')
            ->addSelect(['ultima_entrada' => HistoriaEntrada::select('fecha')
                ->whereColumn('paciente_id', 'pacientes.id')
                ->orderByDesc('fecha')->limit(1)])
            ->orderByDesc('ultima_entrada')
            ->paginate(15);

        return view('panel.historias.indice', ['pacientes' => $pacientes]);
    }

    public function index(Paciente $paciente): View
    {
        return view('panel.historias.index', [
            'paciente' => $paciente,
            'entradas' => HistoriaEntrada::with('adjuntos')
                ->where('paciente_id', $paciente->id)
                ->orderByDesc('fecha')->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function store(Request $request, Paciente $paciente): RedirectResponse
    {
        $datos = $this->validar($request);

        $entrada = HistoriaEntrada::create([
            'paciente_id' => $paciente->id,
            'fecha' => $datos['fecha'],
            'texto' => $datos['texto'] ?? null,
        ]);

        $this->guardarAdjuntos($request, $entrada);

        return redirect()->route('panel.pacientes.historia', $paciente)->with('exito', 'Entrada añadida a la historia.');
    }

    public function edit(HistoriaEntrada $entrada): View
    {
        $entrada->load('adjuntos');

        return view('panel.historias.edit', [
            'entrada' => $entrada,
            'paciente' => $entrada->paciente,
        ]);
    }

    public function update(Request $request, HistoriaEntrada $entrada): RedirectResponse
    {
        $datos = $this->validar($request);

        $entrada->update([
            'fecha' => $datos['fecha'],
            'texto' => $datos['texto'] ?? null,
        ]);

        $this->guardarAdjuntos($request, $entrada);

        return redirect()->route('panel.pacientes.historia', $entrada->paciente_id)->with('exito', 'Entrada actualizada.');
    }

    public function destroy(HistoriaEntrada $entrada): RedirectResponse
    {
        $pacienteId = $entrada->paciente_id;

        foreach ($entrada->adjuntos as $adjunto) {
            Storage::disk('local')->delete($adjunto->ruta);
        }
        $entrada->delete();

        return redirect()->route('panel.pacientes.historia', $pacienteId)->with('exito', 'Entrada eliminada.');
    }

    public function eliminarAdjunto(HistoriaAdjunto $adjunto): RedirectResponse
    {
        $pacienteId = $adjunto->entrada->paciente_id;

        Storage::disk('local')->delete($adjunto->ruta);
        $adjunto->delete();

        return redirect()->route('panel.pacientes.historia', $pacienteId)->with('exito', 'Adjunto eliminado.');
    }

    public function verAdjunto(HistoriaAdjunto $adjunto): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($adjunto->ruta), 404);

        return Storage::disk('local')->response($adjunto->ruta, $adjunto->nombre, [
            'Content-Disposition' => 'inline; filename="'.$adjunto->nombre.'"',
        ]);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'fecha' => ['required', 'date'],
            'texto' => ['nullable', 'string'],
            'adjuntos' => ['nullable', 'array'],
            'adjuntos.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:8192'],
        ]);
    }

    private function guardarAdjuntos(Request $request, HistoriaEntrada $entrada): void
    {
        if (! $request->hasFile('adjuntos')) {
            return;
        }

        foreach ($request->file('adjuntos') as $archivo) {
            $extension = strtolower($archivo->getClientOriginalExtension());
            $tipo = $extension === 'pdf' ? 'pdf' : 'imagen';
            $ruta = $archivo->store('historias/'.$entrada->paciente_id, 'local');

            $entrada->adjuntos()->create([
                'ruta' => $ruta,
                'nombre' => $archivo->getClientOriginalName(),
                'tipo' => $tipo,
            ]);
        }
    }
}
