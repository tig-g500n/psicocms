<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ImagenWeb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ImagenController extends Controller
{
    public function index(): View
    {
        $overrides = ImagenWeb::overrides();

        return view('panel.imagenes.index', [
            'grupos' => ImagenWeb::catalogo(),
            'overrides' => $overrides,
        ]);
    }

    public function actualizar(Request $request, string $clave): RedirectResponse
    {
        abort_unless(array_key_exists($clave, ImagenWeb::slots()), 404);

        $validador = Validator::make($request->all(), [
            'imagen' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'imagen.required' => 'No se recibió ninguna imagen (puede que supere el tamaño máximo permitido por el servidor).',
            'imagen.image' => 'El archivo seleccionado no es una imagen válida.',
            'imagen.mimes' => 'Formato no válido. Usa JPG, PNG o WEBP (las fotos HEIC del iPhone no funcionan; conviértelas antes).',
            'imagen.max' => 'La imagen es demasiado grande. El máximo son 8 MB.',
        ]);

        if ($validador->fails()) {
            return back()->with('error', $validador->errors()->first('imagen'));
        }

        $archivo = $request->file('imagen');

        ImagenWeb::updateOrCreate(['clave' => $clave], [
            'ruta' => $archivo->getClientOriginalName(),
            'mime' => $archivo->getMimeType(),
            'contenido_base64' => base64_encode(file_get_contents($archivo->getRealPath())),
        ]);

        return back()->with('exito', 'Imagen actualizada.');
    }

    public function restablecer(string $clave): RedirectResponse
    {
        $registro = ImagenWeb::where('clave', $clave)->first();

        $registro?->delete();

        return back()->with('exito', 'Imagen restablecida a la de por defecto.');
    }

    public function mostrar(string $clave): Response
    {
        $imagen = ImagenWeb::where('clave', $clave)->firstOrFail();
        abort_unless($imagen->tieneContenido(), 404);

        $contenido = base64_decode($imagen->contenido_base64, true);
        abort_if($contenido === false, 404);

        return response($contenido, 200, [
            'Content-Type' => $imagen->mime,
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
