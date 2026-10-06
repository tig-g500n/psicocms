<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\PlantillaProteccionDatos;
use App\Services\ProteccionDatosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProteccionDatosController extends Controller
{
    public function __construct(private readonly ProteccionDatosService $servicio)
    {
    }

    public function index(): View
    {
        return view('panel.proteccion-datos.index', [
            'plantilla' => PlantillaProteccionDatos::singleton(),
            'placeholders' => $this->servicio->placeholders(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'contenido' => ['nullable', 'string'],
        ]);

        PlantillaProteccionDatos::singleton()->update(['contenido' => $datos['contenido'] ?? '']);

        return back()->with('exito', 'Plantilla de protección de datos guardada.');
    }

    public function pdfVacio(): Response
    {
        $html = $this->servicio->reemplazar(PlantillaProteccionDatos::singleton()->contenido ?? '', null);

        return $this->pdf($html, 'plantilla-proteccion-datos.pdf');
    }

    public function pdfPaciente(Paciente $paciente): Response
    {
        $html = $this->servicio->reemplazar(PlantillaProteccionDatos::singleton()->contenido ?? '', $paciente);

        return $this->pdf($html, 'proteccion-datos-'.Str::slug($paciente->nombre).'.pdf');
    }

    private function pdf(string $contenido, string $nombre): Response
    {
        return Pdf::loadView('pdf.proteccion-datos', ['contenido' => $contenido])
            ->setPaper('a4')
            ->download($nombre);
    }
}
