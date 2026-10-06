@extends('panel.layouts.app')

@section('titulo', 'Mi perfil')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Mi perfil"
        subtitulo="Tus datos privados de acceso. No se muestran en la web pública.">
    </x-panel.page-header>

    <form method="POST" action="{{ route('panel.perfil.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-panel.card>
            <h2 class="ajuste__grupo"><i class="fa-solid fa-image-portrait"></i> Avatar</h2>
            <label class="perfil-avatar" for="avatar">
                <span class="perfil-avatar__preview" data-img-preview>
                    @if ($psicologa->avatar)
                        <img src="{{ asset($psicologa->avatar) }}" alt="Avatar">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                </span>
                <span class="perfil-avatar__text">
                    <strong>Cambiar avatar</strong>
                    <span class="campo__hint">JPG, PNG o WEBP, máx. 4 MB.</span>
                </span>
                <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp" hidden
                    data-img-input="[data-img-preview]">
            </label>
            @error('avatar')<span class="campo__error">{{ $message }}</span>@enderror
        </x-panel.card>

        <x-panel.card>
            <h2 class="ajuste__grupo"><i class="fa-solid fa-id-card"></i> Datos personales</h2>
            <div class="ajuste-grid">
                <div class="campo">
                    <label class="campo__label" for="nombre">Nombre</label>
                    <input class="campo__input" id="nombre" type="text" name="nombre" value="{{ old('nombre', $psicologa->nombre) }}" required>
                    @error('nombre')<span class="campo__error">{{ $message }}</span>@enderror
                </div>
                <div class="campo">
                    <label class="campo__label" for="apellidos">Apellidos</label>
                    <input class="campo__input" id="apellidos" type="text" name="apellidos" value="{{ old('apellidos', $psicologa->apellidos) }}" required>
                    @error('apellidos')<span class="campo__error">{{ $message }}</span>@enderror
                </div>
                <div class="campo">
                    <label class="campo__label" for="email_privado">Email de acceso</label>
                    <input class="campo__input" id="email_privado" type="email" name="email_privado" value="{{ old('email_privado', $psicologa->email_privado) }}" required>
                    @error('email_privado')<span class="campo__error">{{ $message }}</span>@enderror
                </div>
                <div class="campo">
                    <label class="campo__label" for="telefono_privado">Teléfono de acceso</label>
                    <input class="campo__input" id="telefono_privado" type="text" name="telefono_privado" value="{{ old('telefono_privado', $psicologa->telefono_privado) }}" required>
                    @error('telefono_privado')<span class="campo__error">{{ $message }}</span>@enderror
                </div>
            </div>
        </x-panel.card>

        <x-panel.card>
            <h2 class="ajuste__grupo"><i class="fa-solid fa-lock"></i> Cambiar contraseña</h2>
            <p class="campo__hint" style="margin-bottom:1.6rem;">Déjalo vacío si no quieres cambiarla.</p>
            <div class="ajuste-grid">
                <div class="campo">
                    <label class="campo__label" for="password">Nueva contraseña</label>
                    <input class="campo__input" id="password" type="password" name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                    @error('password')<span class="campo__error">{{ $message }}</span>@enderror
                </div>
                <div class="campo">
                    <label class="campo__label" for="password_confirmation">Repite la contraseña</label>
                    <input class="campo__input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
        </x-panel.card>

        <div class="ajuste__acciones">
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar cambios</button>
        </div>
    </form>
@endsection
