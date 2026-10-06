@extends('panel.layouts.app')

@section('titulo', 'Redes sociales')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Redes sociales"
        subtitulo="Añade los enlaces a tus redes. Solo aparecerán en tu web pública las que tengan un enlace.">
    </x-panel.page-header>

    <form method="POST" action="{{ route('panel.redes.guardar') }}">
        @csrf
        @method('PUT')

        <x-panel.card>
            <div class="redes-lista">
                @foreach ($redes as $red)
                    @php($valor = old('redes.'.$red['clave'], $valores[$red['clave']] ?? ''))
                    <div class="red-fila">
                        <div class="red-fila__icono red-fila__icono--{{ $red['clave'] }}"><i class="fa-brands {{ $red['icono'] }}"></i></div>
                        <div class="red-fila__campo">
                            <label class="campo__label" for="red-{{ $red['clave'] }}">{{ $red['label'] }}</label>
                            <input class="campo__input" id="red-{{ $red['clave'] }}" type="url" name="redes[{{ $red['clave'] }}]"
                                value="{{ $valor }}" placeholder="{{ $red['placeholder'] }}">
                            @error('redes.'.$red['clave'])
                                <span class="campo__error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="ajuste__acciones">
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar redes</button>
            </div>
        </x-panel.card>
    </form>
@endsection
