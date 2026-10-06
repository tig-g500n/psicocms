<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionWeb;
use App\Services\Notificador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function __construct(private Notificador $notificador)
    {
    }

    public function index(): View
    {
        return view('panel.notificaciones.index', [
            'notif' => $this->notificador->config(),
            'configurada' => $this->notificador->configurada(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $request->validate([
            'notif_email' => ['nullable', 'email', 'max:255'],
            'notif_gmail_user' => ['nullable', 'email', 'max:255'],
            'notif_gmail_password' => ['nullable', 'string', 'max:255'],
            'notif_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        ConfiguracionWeb::guardar('notif_activa', $request->boolean('notif_activa') ? '1' : '0');
        ConfiguracionWeb::guardar('notif_email', trim((string) $request->input('notif_email', '')));
        ConfiguracionWeb::guardar('notif_gmail_user', trim((string) $request->input('notif_gmail_user', '')));
        ConfiguracionWeb::guardar('notif_from_name', trim((string) $request->input('notif_from_name', '')));

        if ($request->filled('notif_gmail_password')) {
            ConfiguracionWeb::guardar('notif_gmail_password', Crypt::encryptString($request->input('notif_gmail_password')));
        }

        return back()->with('exito', 'Configuración de notificaciones guardada.');
    }

    public function prueba(): RedirectResponse
    {
        if (! $this->notificador->configurada()) {
            return back()->with('error', 'Completa el usuario de Gmail, la contraseña de aplicación y el email de destino antes de enviar la prueba.');
        }

        try {
            $this->notificador->enviarPrueba();

            return back()->with('exito', 'Correo de prueba enviado. Revisa tu bandeja de entrada.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo enviar el correo: '.$e->getMessage());
        }
    }
}
