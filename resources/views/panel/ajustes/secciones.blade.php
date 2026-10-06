@extends('panel.layouts.app')

@section('titulo', 'Secciones de la web')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/secciones.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Secciones de la web"
        subtitulo="Activa o desactiva qué secciones se muestran en tu web pública. Podrás seguir gestionando su contenido aunque estén ocultas.">
    </x-panel.page-header>

    <form method="POST" action="{{ route('panel.secciones.guardar') }}">
        @csrf
        @method('PUT')

        <x-panel.card>
            <div class="secciones-lista">
                @foreach ($secciones as $seccion)
                    <div class="seccion-fila">
                        <div class="seccion-fila__icono"><i class="fa-solid {{ $seccion['icono'] }}"></i></div>
                        <div class="seccion-fila__info">
                            <span class="seccion-fila__label">{{ $seccion['label'] }}</span>
                            <span class="seccion-fila__desc">{{ $seccion['descripcion'] }}</span>
                        </div>
                        <x-panel.toggle name="secciones[]" :value="$seccion['clave']" :checked="$estado[$seccion['clave']]" />
                    </div>
                @endforeach
            </div>

            <div class="secciones-acciones">
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar cambios</button>
            </div>
        </x-panel.card>
    </form>
@endsection
