@extends('panel.layouts.app')

@section('titulo', 'Frases públicas')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Frases públicas"
        subtitulo="Personaliza los textos que aparecen por defecto en tu web. Si dejas uno vacío, se usará el texto original.">
    </x-panel.page-header>

    <form method="POST" action="{{ route('panel.frases.guardar') }}">
        @csrf
        @method('PUT')

        @foreach ($grupos as $grupo)
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid {{ $grupo['icono'] }}"></i> {{ $grupo['label'] }}</h2>

                @foreach ($grupo['items'] as $item)
                    @php($valor = old('frases.'.$item['clave'], $overrides[$item['clave']] ?? $item['defecto']))
                    <div class="campo">
                        <label class="campo__label" for="frase-{{ $item['clave'] }}">{{ $item['label'] }}</label>
                        @if ($item['largo'] ?? false)
                            <textarea class="campo__input" id="frase-{{ $item['clave'] }}" name="frases[{{ $item['clave'] }}]" rows="2">{{ $valor }}</textarea>
                        @else
                            <input class="campo__input" id="frase-{{ $item['clave'] }}" type="text" name="frases[{{ $item['clave'] }}]" value="{{ $valor }}">
                        @endif
                        <span class="campo__hint">Por defecto: {{ $item['defecto'] }}</span>
                    </div>
                @endforeach
            </x-panel.card>
        @endforeach

        <div class="ajuste__acciones">
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar frases</button>
        </div>
    </form>
@endsection
