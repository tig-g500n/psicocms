<?php

namespace App\Services;

use App\Models\ConfiguracionWeb;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class Notificador
{
    public function config(): array
    {
        $password = ConfiguracionWeb::obtener('notif_gmail_password');

        if ($password) {
            try {
                $password = Crypt::decryptString($password);
            } catch (\Throwable $e) {
                $password = null;
            }
        }

        return [
            'activa' => ConfiguracionWeb::obtener('notif_activa', '0') === '1',
            'email' => ConfiguracionWeb::obtener('notif_email'),
            'gmail_user' => ConfiguracionWeb::obtener('notif_gmail_user'),
            'password' => $password,
            'from_name' => ConfiguracionWeb::obtener('notif_from_name') ?: config('psicocms.nombre'),
        ];
    }

    public function configurada(): bool
    {
        $c = $this->config();

        return ! empty($c['gmail_user']) && ! empty($c['password']) && ! empty($c['email']);
    }

    public function activa(): bool
    {
        return $this->config()['activa'] && $this->configurada();
    }

    protected function aplicarMailer(array $c): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => 587,
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.mailers.smtp.username' => $c['gmail_user'],
            'mail.mailers.smtp.password' => $c['password'],
            'mail.from.address' => $c['gmail_user'],
            'mail.from.name' => $c['from_name'],
        ]);
    }

    public function enviarPrueba(): void
    {
        $c = $this->config();
        $this->aplicarMailer($c);

        Mail::raw(
            'Este es un correo de prueba de PsicoCMS. Si lo recibes, tus notificaciones por email están correctamente configuradas.',
            function ($mensaje) use ($c) {
                $mensaje->to($c['email'])->subject('Prueba de notificaciones · PsicoCMS');
            }
        );
    }

    public function enviarAviso(string $asunto, string $cuerpo): bool
    {
        if (! $this->activa()) {
            return false;
        }

        $c = $this->config();
        $this->aplicarMailer($c);

        Mail::raw($cuerpo, function ($mensaje) use ($c, $asunto) {
            $mensaje->to($c['email'])->subject($asunto);
        });

        return true;
    }
}
