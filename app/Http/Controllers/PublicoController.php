<?php

namespace App\Http\Controllers;

use App\Models\BlogArticulo;
use App\Models\BlogCategoria;
use App\Models\DisponibilidadSlot;
use App\Models\Especialidad;
use App\Models\Faq;
use App\Models\PerfilPublico;
use App\Models\PlanPrecio;
use App\Models\Servicio;
use App\Services\SlotService;
use App\Services\ThemeManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicoController extends Controller
{
    public function __construct(private ThemeManager $temas, private SlotService $slots)
    {
    }

    public function home(): View
    {
        return view('publico.home', $this->datos());
    }

    public function sobreMi(): View
    {
        abort_unless(seccion_activa('sobre_mi'), 404);

        return view('publico.paginas.sobre-mi', $this->datos());
    }

    public function servicios(): View
    {
        abort_unless(seccion_activa('servicios'), 404);

        return view('publico.paginas.servicios', $this->datos());
    }

    public function faq(): View
    {
        abort_unless(seccion_activa('faq'), 404);

        return view('publico.paginas.faq', $this->datos());
    }

    public function contacto(): View
    {
        abort_unless(seccion_activa('reservas'), 404);

        return view('publico.paginas.contacto', $this->datos());
    }

    public function blog(Request $request): View
    {
        abort_unless(seccion_activa('blog'), 404);

        $categorias = BlogCategoria::withCount(['articulos' => fn ($q) => $q->where('publicado', true)])
            ->orderBy('nombre')->get()
            ->filter(fn ($c) => $c->articulos_count > 0)
            ->values();

        $categoriaActiva = null;
        $query = BlogArticulo::with('categoria')->where('publicado', true);

        if ($request->filled('categoria')) {
            $categoriaActiva = $categorias->firstWhere('slug', $request->categoria);
            if ($categoriaActiva) {
                $query->where('categoria_id', $categoriaActiva->id);
            }
        }

        return view('publico.paginas.blog', array_merge($this->datos(), [
            'articulos' => $query->orderByDesc('fecha')->paginate(6)->withQueryString(),
            'categorias' => $categorias,
            'categoriaActiva' => $categoriaActiva,
        ]));
    }

    public function articulo(BlogArticulo $articulo): View
    {
        abort_unless(seccion_activa('blog') && $articulo->publicado, 404);

        return view('publico.paginas.articulo', array_merge($this->datos(), [
            'articulo' => $articulo->load('categoria'),
        ]));
    }

    private function datos(): array
    {
        $perfil = PerfilPublico::first() ?? new PerfilPublico();
        $manifiesto = $this->temas->manifiesto($this->temas->activo());
        $whatsapp = red_social('whatsapp');

        $modalidadesReserva = collect(['online', 'presencial'])
            ->filter(fn ($m) => DisponibilidadSlot::where('modalidad', $m)->exists())
            ->values();

        return [
            'modalidadesReserva' => $modalidadesReserva,
            'reservaBloqueada' => $this->slots->modoVacaciones(),
            'tema' => $manifiesto,
            'modo' => $this->temas->modoActivo(),
            'perfil' => $perfil,
            'servicios' => seccion_activa('servicios') ? Servicio::where('activo', true)->orderBy('orden')->get() : collect(),
            'especialidades' => seccion_activa('especialidades') ? Especialidad::orderBy('orden')->get() : collect(),
            'planes' => seccion_activa('planes') ? PlanPrecio::orderBy('orden')->get() : collect(),
            'faqs' => seccion_activa('faq') ? Faq::where('activo', true)->orderBy('orden')->get() : collect(),
            'ultimosArticulos' => seccion_activa('blog') ? BlogArticulo::where('publicado', true)->orderByDesc('fecha')->limit(3)->get() : collect(),
            'redes' => redes_configuradas(),
            'whatsapp' => $whatsapp,
            'nombreCompleto' => trim(($perfil->nombre ?? '').' '.($perfil->apellidos ?? '')) ?: config('psicocms.nombre'),
        ];
    }
}
