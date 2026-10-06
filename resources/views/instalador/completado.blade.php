@extends('instalador.layouts.app')

@section('contenido')
    <div class="inst-done">
        <span class="inst-done__icon"><i class="fa-solid fa-circle-check"></i></span>
        <h2 class="inst__title">¡Instalación completada, {{ $nombre }}!</h2>
        <p class="inst__desc">Tu base de datos, tu cuenta y los datos de tu web ya están guardados. Ya puedes acceder al panel con tu email, teléfono y contraseña.</p>

        <div class="inst-done__actions">
            <a href="{{ route('acceso.mostrar') }}" class="inst-btn inst-btn--primary"><i class="fa-solid fa-right-to-bracket"></i> Acceder al panel</a>
            <a href="{{ url('/') }}" class="inst-btn inst-btn--ghost"><i class="fa-solid fa-globe"></i> Ver mi web</a>
        </div>
    </div>
@endsection
