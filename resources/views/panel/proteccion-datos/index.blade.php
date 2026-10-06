@extends('panel.layouts.app')

@section('titulo', 'Protección de datos')

@section('contenido')
    <x-panel.page-header titulo="Documento de protección de datos" subtitulo="Configura la plantilla que se generará en PDF para tus pacientes.">
        <x-slot:acciones>
            <a href="{{ route('panel.proteccion.pdf-vacio') }}" class="btn btn--ghost"><i class="fa-solid fa-file-pdf"></i> Descargar plantilla vacía</a>
        </x-slot:acciones>
    </x-panel.page-header>

    <div class="blog-form">
        <div class="card">
            <form method="POST" action="{{ route('panel.proteccion.guardar') }}">
                @csrf
                @method('PUT')
                <div class="campo">
                    <label class="campo__label" for="contenido">Plantilla del documento</label>
                    <x-panel.wysiwyg name="contenido" :value="old('contenido', $plantilla->contenido)" rows="18" />
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar plantilla</button>
                </div>
            </form>
        </div>

        <div class="blog-form__lado">
            <div class="card">
                <h2 class="card__title" style="margin-bottom:1rem;"><i class="fa-solid fa-tags"></i> Campos disponibles</h2>
                <p class="campo__hint" style="margin-bottom:1.4rem;">Escribe estos códigos en la plantilla; se sustituirán por los datos reales al generar el PDF del paciente.</p>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:1rem;">
                    @foreach ($placeholders as $token => $label)
                        <li>
                            <code style="display:inline-block;background:var(--dash-primary-soft);color:var(--dash-primary);padding:.4rem .8rem;border-radius:.6rem;font-size:1.3rem;">{{ $token }}</code>
                            <div class="campo__hint">{{ $label }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card">
                <h2 class="card__title" style="margin-bottom:1rem;">Cómo funciona</h2>
                <p class="campo__hint" style="line-height:1.7;">Desde el <strong>detalle de cada paciente</strong> podrás descargar este documento con sus datos ya rellenos. La <em>plantilla vacía</em> descarga el documento en blanco para imprimir y rellenar a mano.</p>
            </div>
        </div>
    </div>
@endsection
