@if (seccion_activa('reservas'))
    @php
        $mapaQuery = $perfil->mapa_lat && $perfil->mapa_lng
            ? $perfil->mapa_lat.','.$perfil->mapa_lng
            : ($perfil->direccion ?: null);
    @endphp
    <section id="contacto" class="pp-seccion pp-contacto pp-seccion--fondo" @if (imagen_web('contacto_fondo')) style="--img-fondo: url('{{ imagen_web('contacto_fondo') }}')" @endif>
        <div class="pp-wrap">
            <div class="pp-seccion__head">
                <span class="pp-seccion__eyebrow">{{ frase_publica('reservas_titulo') }}</span>
                <h2 class="pp-seccion__titulo">{{ frase_publica('contacto_titulo') }}</h2>
                <p class="pp-seccion__lead">{{ frase_publica('reservas_texto') }}</p>
            </div>

            <div class="pp-contacto__grid">
                <div class="pp-contacto__reserva" id="reserva">
                    @if (imagen_web('cita'))
                        <img src="{{ imagen_web('cita') }}" alt="Reserva tu cita" class="pp-reserva__banner" loading="lazy">
                    @endif
                    <h3 class="pp-contacto__subtitulo"><i class="fa-solid fa-calendar-check"></i> Reserva tu cita</h3>

                    @if ($reservaBloqueada || $modalidadesReserva->isEmpty())
                        <p class="pp-reserva__aviso">
                            <i class="fa-solid fa-circle-info"></i>
                            @if ($reservaBloqueada)
                                Ahora mismo no acepto reservas online. Escríbeme o llámame y lo vemos.
                            @else
                                Todavía no hay horarios disponibles para reservar online. Contáctame directamente.
                            @endif
                        </p>
                        <div class="pp-contacto__acciones">
                            @if ($perfil->telefono_citas)
                                <a href="tel:{{ $perfil->telefono_citas }}" class="pp-btn pp-btn--primary"><i class="fa-solid fa-phone"></i> Llamar</a>
                            @endif
                            @if ($whatsapp)
                                <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="pp-btn pp-btn--outline"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                            @endif
                            @if ($perfil->email_citas)
                                <a href="mailto:{{ $perfil->email_citas }}" class="pp-btn pp-btn--outline"><i class="fa-solid fa-envelope"></i> Email</a>
                            @endif
                        </div>
                    @else
                        <div class="pp-reserva" data-reserva
                            data-url-dias="{{ route('reservas.dias') }}"
                            data-url-horas="{{ route('reservas.horas') }}"
                            data-url-store="{{ route('reservas.store') }}">

                            <div class="pp-reserva__paso">
                                <span class="pp-reserva__label">1. Tipo de sesión</span>
                                <div class="pp-reserva__modalidades">
                                    @foreach ($modalidadesReserva as $m)
                                        <button type="button" class="pp-reserva__modalidad" data-modalidad="{{ $m }}">
                                            <i class="fa-solid {{ $m === 'online' ? 'fa-video' : 'fa-location-dot' }}"></i> {{ ucfirst($m) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pp-reserva__paso" data-paso-fecha hidden>
                                <span class="pp-reserva__label">2. Elige el día</span>
                                <div class="pp-cal" data-cal>
                                    <div class="pp-cal__head">
                                        <button type="button" data-cal-prev aria-label="Mes anterior"><i class="fa-solid fa-chevron-left"></i></button>
                                        <span class="pp-cal__titulo" data-cal-titulo></span>
                                        <button type="button" data-cal-next aria-label="Mes siguiente"><i class="fa-solid fa-chevron-right"></i></button>
                                    </div>
                                    <div class="pp-cal__semana">
                                        <span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span>
                                    </div>
                                    <div class="pp-cal__dias" data-cal-dias></div>
                                </div>
                            </div>

                            <div class="pp-reserva__paso" data-paso-hora hidden>
                                <span class="pp-reserva__label">3. Elige la hora</span>
                                <div class="pp-reserva__horas" data-horas></div>
                            </div>

                            <form class="pp-reserva__paso" data-reserva-form hidden novalidate>
                                <span class="pp-reserva__label">4. Tus datos</span>
                                <input type="hidden" name="modalidad" data-f-modalidad>
                                <input type="hidden" name="fecha" data-f-fecha>
                                <input type="hidden" name="hora_inicio" data-f-hora>

                                <div class="pp-field">
                                    <label for="r-nombre">Nombre *</label>
                                    <input type="text" id="r-nombre" name="nombre" required>
                                </div>
                                <div class="pp-field">
                                    <label for="r-telefono">Teléfono *</label>
                                    <input type="tel" id="r-telefono" name="telefono" inputmode="numeric" required placeholder="Solo números">
                                </div>
                                <div class="pp-field">
                                    <label for="r-motivo">Motivo de la consulta (opcional)</label>
                                    <textarea id="r-motivo" name="motivo" rows="2"></textarea>
                                </div>

                                <p class="pp-reserva__error" data-reserva-error hidden></p>

                                <button type="submit" class="pp-btn pp-btn--primary" data-reserva-submit>
                                    <i class="fa-solid fa-check"></i> Confirmar reserva
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="pp-contacto__donde">
                    <h3 class="pp-contacto__subtitulo"><i class="fa-solid fa-location-dot"></i> ¿Dónde estamos?</h3>
                    <ul class="pp-contacto__datos">
                        @if ($perfil->lugar_consulta)
                            <li><i class="fa-solid fa-building"></i> {{ $perfil->lugar_consulta }}</li>
                        @endif
                        @if ($perfil->direccion)
                            <li><i class="fa-solid fa-map-pin"></i> {{ $perfil->direccion }}</li>
                        @endif
                        @if ($perfil->telefono_citas)
                            <li><i class="fa-solid fa-phone"></i> {{ $perfil->telefono_citas }}</li>
                        @endif
                        @if ($perfil->email_citas)
                            <li><i class="fa-solid fa-envelope"></i> {{ $perfil->email_citas }}</li>
                        @endif
                    </ul>

                    @if ($mapaQuery)
                        <div class="pp-mapa">
                            <iframe
                                src="https://www.google.com/maps?q={{ urlencode($mapaQuery) }}&output=embed"
                                width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" title="Ubicación de la consulta"></iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="pp-modal" data-reserva-modal hidden>
        <div class="pp-modal__overlay" data-reserva-close></div>
        <div class="pp-modal__dialog" role="dialog" aria-modal="true">
            <div class="pp-modal__icono"><i class="fa-solid fa-circle-check"></i></div>
            <h3 class="pp-modal__titulo">¡Cita reservada!</h3>
            <p class="pp-modal__texto" data-reserva-resumen></p>
            <div class="pp-modal__acciones">
                <a href="#" target="_blank" rel="noopener" class="pp-btn pp-btn--primary" data-reserva-google>
                    <i class="fa-regular fa-calendar-plus"></i> Añadir a Google Calendar
                </a>
                <button type="button" class="pp-btn pp-btn--outline" data-reserva-close>Cerrar</button>
            </div>
        </div>
    </div>
@endif
