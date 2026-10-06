@extends('panel.layouts.app')

@section('titulo', 'Email y notificaciones')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Email y notificaciones"
        subtitulo="Recibe un aviso en tu correo cada vez que alguien reserve una cita desde tu web.">
    </x-panel.page-header>

    <x-panel.card class="notif-tutorial">
        <h2 class="ajuste__grupo"><i class="fa-solid fa-circle-info"></i> Cómo conseguir tu «contraseña de aplicación» de Gmail</h2>
        <ol class="notif-pasos">
            <li>Entra en tu cuenta de Google y activa la <strong>Verificación en dos pasos</strong> (Seguridad → Verificación en 2 pasos).</li>
            <li>Ve a <strong>Contraseñas de aplicaciones</strong> (myaccount.google.com/apppasswords).</li>
            <li>Crea una nueva para «Correo». Google te dará una clave de <strong>16 letras</strong>.</li>
            <li>Copia esa clave y pégala abajo en «Contraseña de aplicación». Tu contraseña normal de Gmail no sirve aquí.</li>
        </ol>
    </x-panel.card>

    <form method="POST" action="{{ route('panel.notificaciones.guardar') }}">
        @csrf
        @method('PUT')

        <x-panel.card>
            <div class="notif-toggle">
                <div>
                    <span class="seccion-fila__label">Activar notificaciones por email</span>
                    <span class="seccion-fila__desc">Si está desactivado, no se enviará ningún correo.</span>
                </div>
                <x-panel.toggle name="notif_activa" :checked="$notif['activa']" />
            </div>

            <div class="campo">
                <label class="campo__label" for="notif_gmail_user">Tu Gmail (desde el que se envía)</label>
                <input class="campo__input" id="notif_gmail_user" type="email" name="notif_gmail_user"
                    value="{{ old('notif_gmail_user', $notif['gmail_user']) }}" placeholder="tucorreo@gmail.com">
                @error('notif_gmail_user')<span class="campo__error">{{ $message }}</span>@enderror
            </div>

            <div class="campo">
                <label class="campo__label" for="notif_gmail_password">Contraseña de aplicación (16 letras)</label>
                <input class="campo__input" id="notif_gmail_password" type="password" name="notif_gmail_password"
                    autocomplete="new-password" placeholder="{{ $notif['password'] ? '•••• ya configurada — déjalo vacío para mantenerla' : 'xxxx xxxx xxxx xxxx' }}">
                <span class="campo__hint">Se guarda cifrada. Déjala vacía si no quieres cambiarla.</span>
            </div>

            <div class="campo">
                <label class="campo__label" for="notif_email">Email donde quieres recibir los avisos</label>
                <input class="campo__input" id="notif_email" type="email" name="notif_email"
                    value="{{ old('notif_email', $notif['email']) }}" placeholder="tucorreo@gmail.com">
                @error('notif_email')<span class="campo__error">{{ $message }}</span>@enderror
            </div>

            <div class="campo">
                <label class="campo__label" for="notif_from_name">Nombre del remitente (opcional)</label>
                <input class="campo__input" id="notif_from_name" type="text" name="notif_from_name"
                    value="{{ old('notif_from_name', $notif['from_name']) }}" placeholder="{{ config('psicocms.nombre') }}">
            </div>

            <div class="ajuste__acciones">
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar configuración</button>
            </div>
        </x-panel.card>
    </form>

    <x-panel.card>
        <div class="notif-prueba">
            <div>
                <span class="seccion-fila__label">Probar el envío</span>
                <span class="seccion-fila__desc">
                    @if ($configurada)
                        Enviaremos un correo de prueba a <strong>{{ $notif['email'] }}</strong>.
                    @else
                        Guarda primero el Gmail, la contraseña de aplicación y el email de destino.
                    @endif
                </span>
            </div>
            <form method="POST" action="{{ route('panel.notificaciones.prueba') }}">
                @csrf
                <button type="submit" class="btn btn--ghost" {{ $configurada ? '' : 'disabled' }}>
                    <i class="fa-solid fa-paper-plane"></i> Enviar prueba
                </button>
            </form>
        </div>
    </x-panel.card>
@endsection
