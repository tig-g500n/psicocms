@extends('panel.layouts.app')

@section('titulo', 'Buscar')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/ajustes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Resultados de búsqueda"
        subtitulo="{{ $q !== '' ? 'Coincidencias para «'.$q.'»' : 'Escribe algo en el buscador de arriba.' }}">
    </x-panel.page-header>

    @if (mb_strlen($q) < 2)
        <x-panel.empty-state icono="fa-magnifying-glass" titulo="Empieza a buscar"
            texto="Introduce al menos 2 caracteres en el buscador del encabezado para encontrar pacientes, citas, historias, artículos y preguntas frecuentes." />
    @elseif ($total === 0)
        <x-panel.empty-state icono="fa-face-frown" titulo="Sin resultados"
            texto="No hemos encontrado nada para «{{ $q }}». Prueba con otras palabras." />
    @else
        <p class="buscador__resumen">{{ $total }} {{ $total === 1 ? 'resultado' : 'resultados' }} encontrados.</p>

        @if ($resultados['pacientes']->isNotEmpty())
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid fa-user-group"></i> Pacientes ({{ $resultados['pacientes']->count() }})</h2>
                <div class="buscador-lista">
                    @foreach ($resultados['pacientes'] as $paciente)
                        <a href="{{ route('panel.pacientes.show', $paciente) }}" class="buscador-item">
                            <span class="buscador-item__titulo">{{ $paciente->nombre }}</span>
                            <span class="buscador-item__meta">{{ $paciente->telefono }}{{ $paciente->email ? ' · '.$paciente->email : '' }}</span>
                        </a>
                    @endforeach
                </div>
            </x-panel.card>
        @endif

        @if ($resultados['citas']->isNotEmpty())
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid fa-calendar-check"></i> Citas ({{ $resultados['citas']->count() }})</h2>
                <div class="buscador-lista">
                    @foreach ($resultados['citas'] as $cita)
                        <a href="{{ route('panel.citas.edit', $cita) }}" class="buscador-item">
                            <span class="buscador-item__titulo">{{ $cita->paciente->nombre ?? 'Cita' }}</span>
                            <span class="buscador-item__meta">{{ $cita->fecha->format('d/m/Y') }} · {{ $cita->hora_inicio }} · {{ ucfirst($cita->modalidad) }}</span>
                        </a>
                    @endforeach
                </div>
            </x-panel.card>
        @endif

        @if ($resultados['historias']->isNotEmpty())
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid fa-notes-medical"></i> Historias ({{ $resultados['historias']->count() }})</h2>
                <div class="buscador-lista">
                    @foreach ($resultados['historias'] as $entrada)
                        <a href="{{ route('panel.pacientes.historia', $entrada->paciente) }}" class="buscador-item">
                            <span class="buscador-item__titulo">{{ $entrada->paciente->nombre ?? 'Historia' }}</span>
                            <span class="buscador-item__meta">{{ $entrada->fecha->format('d/m/Y') }} · {{ \Illuminate\Support\Str::limit(strip_tags($entrada->texto), 80) }}</span>
                        </a>
                    @endforeach
                </div>
            </x-panel.card>
        @endif

        @if ($resultados['articulos']->isNotEmpty())
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid fa-newspaper"></i> Artículos ({{ $resultados['articulos']->count() }})</h2>
                <div class="buscador-lista">
                    @foreach ($resultados['articulos'] as $articulo)
                        <a href="{{ route('panel.blog.articulos.edit', $articulo) }}" class="buscador-item">
                            <span class="buscador-item__titulo">{{ $articulo->titulo }}</span>
                            <span class="buscador-item__meta">{{ $articulo->publicado ? 'Publicado' : 'Borrador' }}</span>
                        </a>
                    @endforeach
                </div>
            </x-panel.card>
        @endif

        @if ($resultados['faqs']->isNotEmpty())
            <x-panel.card>
                <h2 class="ajuste__grupo"><i class="fa-solid fa-circle-question"></i> Preguntas frecuentes ({{ $resultados['faqs']->count() }})</h2>
                <div class="buscador-lista">
                    @foreach ($resultados['faqs'] as $faq)
                        <a href="{{ route('panel.faq.edit', $faq) }}" class="buscador-item">
                            <span class="buscador-item__titulo">{{ $faq->pregunta }}</span>
                            <span class="buscador-item__meta">{{ \Illuminate\Support\Str::limit(strip_tags($faq->respuesta), 80) }}</span>
                        </a>
                    @endforeach
                </div>
            </x-panel.card>
        @endif
    @endif
@endsection
