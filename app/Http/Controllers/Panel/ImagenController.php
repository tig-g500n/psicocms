<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ImagenWeb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $directorio = config('imagenes.directorio_subidas');
        $nombre = $clave.'_'.uniqid().'.'.$request->file('imagen')->getClientOriginalExtension();
        $request->file('imagen')->move(public_path($directorio), $nombre);
        $ruta = $directorio.'/'.$nombre;

        $anterior = ImagenWeb::where('clave', $clave)->value('ruta');
        if ($anterior && is_file(public_path($anterior))) {
            @unlink(public_path($anterior));
        }

        ImagenWeb::updateOrCreate(['clave' => $clave], ['ruta' => $ruta]);

        return back()->with('exito', 'Imagen actualizada.');
    }

    public function restablecer(string $clave): RedirectResponse
    {
        $registro = ImagenWeb::where('clave', $clave)->first();

        if ($registro) {
            if (is_file(public_path($registro->ruta))) {
                @unlink(public_path($registro->ruta));
            }
            $registro->delete();
        }

        return back()->with('exito', 'Imagen restablecida a la de por defecto.');
    }
}
