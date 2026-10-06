<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PerfilPrivadoController extends Controller
{
    public function edit(): View
    {
        return view('panel.perfil.edit', [
            'psicologa' => auth()->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $psicologa = auth()->user();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email_privado' => ['required', 'email', 'max:255', Rule::unique('psicologa', 'email_privado')->ignore($psicologa->id)],
            'telefono_privado' => ['required', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $psicologa->nombre = $datos['nombre'];
        $psicologa->apellidos = $datos['apellidos'];
        $psicologa->email_privado = $datos['email_privado'];
        $psicologa->telefono_privado = $datos['telefono_privado'];

        if ($request->filled('password')) {
            $psicologa->password = $datos['password'];
        }

        if ($request->hasFile('avatar')) {
            $nombre = 'avatar_'.uniqid().'.'.$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('uploads/perfil'), $nombre);

            if ($psicologa->avatar && is_file(public_path($psicologa->avatar))) {
                @unlink(public_path($psicologa->avatar));
            }

            $psicologa->avatar = 'uploads/perfil/'.$nombre;
        }

        $psicologa->save();

        return back()->with('exito', 'Tu perfil se ha actualizado correctamente.');
    }
}
