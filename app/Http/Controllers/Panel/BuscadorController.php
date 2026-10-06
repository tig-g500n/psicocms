<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlogArticulo;
use App\Models\Cita;
use App\Models\Faq;
use App\Models\HistoriaEntrada;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuscadorController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $resultados = [
            'pacientes' => collect(),
            'citas' => collect(),
            'historias' => collect(),
            'articulos' => collect(),
            'faqs' => collect(),
        ];

        if (mb_strlen($q) >= 2) {
            $like = '%'.$q.'%';

            $resultados['pacientes'] = Paciente::where('nombre', 'like', $like)
                ->orWhere('telefono', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->limit(15)->get();

            $resultados['citas'] = Cita::with('paciente')
                ->where(function ($consulta) use ($like) {
                    $consulta->whereHas('paciente', fn ($p) => $p->where('nombre', 'like', $like)->orWhere('telefono', 'like', $like))
                        ->orWhere('notas', 'like', $like);
                })
                ->orderByDesc('fecha')->limit(15)->get();

            $resultados['historias'] = HistoriaEntrada::with('paciente')
                ->where('texto', 'like', $like)
                ->orderByDesc('fecha')->limit(15)->get();

            $resultados['articulos'] = BlogArticulo::where('titulo', 'like', $like)
                ->orWhere('extracto', 'like', $like)
                ->orWhere('contenido', 'like', $like)
                ->limit(15)->get();

            $resultados['faqs'] = Faq::where('pregunta', 'like', $like)
                ->orWhere('respuesta', 'like', $like)
                ->limit(15)->get();
        }

        $total = collect($resultados)->sum(fn ($c) => $c->count());

        return view('panel.buscador.index', [
            'q' => $q,
            'resultados' => $resultados,
            'total' => $total,
        ]);
    }
}
