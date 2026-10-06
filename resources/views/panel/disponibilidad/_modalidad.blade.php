<div class="disp-panel" data-modalidad="{{ $modalidad }}" @if($modalidad !== 'online') hidden @endif>
    <form method="POST" action="{{ route('panel.disponibilidad.guardar', $modalidad) }}" class="disp-form" data-disp-form>
        @csrf

        <div class="disp-config">
            <div class="campo">
                <label class="campo__label" for="duracion_{{ $modalidad }}">Duración de la sesión (min)</label>
                <input class="campo__input" type="number" min="5" max="600" id="duracion_{{ $modalidad }}" name="duracion_min" value="{{ $config->duracion_min }}" data-duracion required>
            </div>

            <div class="campo">
                <label class="campo__label" for="entrada_{{ $modalidad }}">Hora de entrada</label>
                <input class="campo__input" type="time" id="entrada_{{ $modalidad }}" name="hora_entrada" value="{{ substr($config->hora_entrada, 0, 5) }}" data-entrada required>
            </div>

            <div class="campo">
                <label class="campo__label" for="salida_{{ $modalidad }}">Hora de salida (máxima)</label>
                <input class="campo__input" type="time" id="salida_{{ $modalidad }}" name="hora_salida" value="{{ substr($config->hora_salida, 0, 5) }}" data-salida required>
            </div>

            <div class="campo">
                <label class="campo__label">Descanso entre sesiones</label>
                <div style="display:flex;align-items:center;gap:1.2rem;">
                    <x-panel.toggle :name="'descanso_activo'" :checked="$config->descanso_activo" data-descanso-activo />
                    <input class="campo__input" style="max-width:10rem;" type="number" min="0" max="240" name="descanso_min" value="{{ $config->descanso_min }}" data-descanso aria-label="Minutos de descanso">
                    <span class="campo__hint">minutos</span>
                </div>
            </div>
        </div>

        <div class="disp-aviso" data-aviso hidden>
            <i class="fa-solid fa-triangle-exclamation"></i>
            Has cambiado la duración, el descanso o el horario: revisa y vuelve a marcar tus huecos antes de guardar.
        </div>

        <div class="disp-grid" data-grid>
            @foreach ($grid as $dia => $info)
                <div class="disp-col">
                    <div class="disp-col__head">{{ $info['nombre'] }}</div>
                    <div class="disp-col__slots">
                        @forelse ($info['horas'] as $slot)
                            <label class="disp-slot">
                                <input type="checkbox" name="slots[]" value="{{ $dia }}|{{ $slot['hora'] }}" {{ $slot['marcado'] ? 'checked' : '' }}>
                                <span>{{ $slot['hora'] }}</span>
                            </label>
                        @empty
                            <span class="disp-col__vacio">—</span>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <div class="disp-actions">
            <p class="campo__hint">Los huecos se generan cada {{ $config->pasoMinutos() }} min (duración{{ $config->descanso_activo ? ' + descanso' : '' }}).</p>
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar disponibilidad {{ $modalidad }}</button>
        </div>
    </form>
</div>
