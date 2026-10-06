@if (seccion_activa('blog') && $ultimosArticulos->isNotEmpty())
    <section id="blog" class="pp-seccion pp-seccion--alt">
        <div class="pp-wrap">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">Blog</span>
                <h2 class="pp-seccion__titulo">Últimos artículos</h2>
            </div>

            <div class="pp-cards pp-cards--blog">
                @foreach ($ultimosArticulos as $articulo)
                    <a href="{{ route('publico.articulo', $articulo->slug) }}" class="pp-articulo">
                        <span class="pp-articulo__img">
                            @if ($articulo->imagen)
                                <img src="{{ asset($articulo->imagen) }}" alt="{{ $articulo->titulo }}" loading="lazy">
                            @else
                                <i class="fa-solid fa-newspaper"></i>
                            @endif
                        </span>
                        <span class="pp-articulo__cuerpo">
                            @if ($articulo->categoria)
                                <span class="pp-articulo__cat">{{ $articulo->categoria->nombre }}</span>
                            @endif
                            <span class="pp-articulo__titulo">{{ $articulo->titulo }}</span>
                            @if ($articulo->extracto)
                                <span class="pp-articulo__extracto">{{ \Illuminate\Support\Str::limit($articulo->extracto, 110) }}</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="pp-seccion__mas">
                <a href="{{ route('publico.blog') }}" class="pp-btn pp-btn--outline">Ver todos los artículos</a>
            </div>
        </div>
    </section>
@endif
