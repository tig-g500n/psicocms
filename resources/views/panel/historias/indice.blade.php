@extends('panel.layouts.app')

@section('titulo', 'Historias clínicas')

@push('estilos')
    <link href="{{ asset('assets/dashboard/css/pacientes.css') }}" rel="stylesheet">
@endpush

@section('contenido')
    <x-panel.page-header titulo="Historias clínicas"
        subtitulo="Pacientes con seguimiento registrado. Las historias se crean y editan desde la ficha de cada paciente.">
    </x-panel.page-header>

    @if ($pacientes->isEmpty())
        <x-panel.empty-state icono="fa-notes-medical" titulo="Aún no hay historias"
            texto="Abre un paciente desde «Pacientes» y añade su primera entrada de seguimiento para empezar su historia." />
    @else
        <x-panel.card>
            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Teléfono</th>
                            <th>Entradas</th>
                            <th>Última sesión</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                            <tr>
                                <td><strong>{{ $paciente->nombre }}</strong></td>
                                <td>{{ $paciente->telefono }}</td>
                                <td><span class="badge">{{ $paciente->entradas_count }}</span></td>
                                <td>{{ $paciente->ultima_entrada ? \Carbon\Carbon::parse($paciente->ultima_entrada)->format('d/m/Y') : '—' }}</td>
                                <td class="tabla__acciones">
                                    <a href="{{ route('panel.pacientes.historia', $paciente) }}" class="btn btn--soft btn--sm">
                                        <i class="fa-solid fa-folder-open"></i> Abrir historia
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($pacientes->hasPages())
                <div class="paginacion">
                    @if ($pacientes->onFirstPage())
                        <span class="is-disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $pacientes->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                    @endif
                    @foreach ($pacientes->getUrlRange(1, $pacientes->lastPage()) as $pagina => $url)
                        <a href="{{ $url }}" class="{{ $pagina == $pacientes->currentPage() ? 'is-active' : '' }}">{{ $pagina }}</a>
                    @endforeach
                    @if ($pacientes->hasMorePages())
                        <a href="{{ $pacientes->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <span class="is-disabled"><i class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        </x-panel.card>
    @endif
@endsection
