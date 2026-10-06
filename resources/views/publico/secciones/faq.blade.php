@if (seccion_activa('faq') && $faqs->isNotEmpty())
    <section id="faq" class="pp-seccion">
        <div class="pp-wrap pp-wrap--estrecho">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">Preguntas frecuentes</span>
                <h2 class="pp-seccion__titulo">Resolvemos tus dudas</h2>
            </div>

            <div class="pp-faq">
                @foreach ($faqs as $faq)
                    <details class="pp-faq__item">
                        <summary class="pp-faq__pregunta">
                            {{ $faq->pregunta }}
                            <i class="fa-solid fa-chevron-down"></i>
                        </summary>
                        <div class="pp-faq__respuesta pp-richtext">{!! $faq->respuesta !!}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endif
