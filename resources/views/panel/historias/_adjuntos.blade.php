@if ($adjuntos->isNotEmpty())
    <div class="hist-adjuntos">
        @foreach ($adjuntos as $adjunto)
            <div class="hist-adjunto">
                <a href="{{ route('panel.historias.adjunto.ver', $adjunto) }}" target="_blank" rel="noopener" class="hist-adjunto__link">
                    @if ($adjunto->esImagen())
                        <img src="{{ route('panel.historias.adjunto.ver', $adjunto) }}" alt="{{ $adjunto->nombre }}">
                    @else
                        <span class="hist-adjunto__pdf"><i class="fa-solid fa-file-pdf"></i></span>
                    @endif
                    <span class="hist-adjunto__nombre">{{ $adjunto->nombre }}</span>
                </a>
                @if (($borrable ?? false))
                    <form method="POST" action="{{ route('panel.historias.adjunto.eliminar', $adjunto) }}" class="hist-adjunto__borrar" data-confirm="¿Eliminar el adjunto «{{ $adjunto->nombre }}»?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" aria-label="Eliminar adjunto"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endif
