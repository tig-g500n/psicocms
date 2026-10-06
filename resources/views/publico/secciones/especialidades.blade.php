@if (seccion_activa('especialidades') && $especialidades->isNotEmpty())
    <section id="especialidades" class="pp-seccion pp-seccion--fondo" @if (imagen_web('especialidades')) style="--img-fondo: url('{{ imagen_web('especialidades') }}')" @endif>
        <div class="pp-wrap">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">Especialidades</span>
                <h2 class="pp-seccion__titulo">En qué puedo acompañarte</h2>
            </div>

            <div class="pp-especialidades">
                @foreach ($especialidades as $especialidad)
                    <div class="pp-especialidad">
                        <i class="fa-solid fa-check"></i>
                        <div>
                            <h3>{{ $especialidad->titulo }}</h3>
                            @if ($especialidad->descripcion)
                                <p>{{ $especialidad->descripcion }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
