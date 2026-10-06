<div class="modal" id="modal-apariencia" data-modal>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true">
        <h2 class="modal__title">Apariencia del panel</h2>
        <p class="apar-intro">Personaliza el aspecto de tu panel. Se guarda y se mantiene aunque cierres sesión.</p>

        <form method="POST" action="{{ route('panel.apariencia.guardar') }}">
            @csrf

            <div class="modal__body">
                <div class="apar-seccion">
                    <span class="apar-label">Modo</span>
                    <div class="apar-modos">
                        <label class="apar-modo">
                            <input type="radio" name="apariencia" value="claro" {{ $apariencia === 'claro' ? 'checked' : '' }} hidden>
                            <span class="apar-modo__card"><i class="fa-solid fa-sun"></i> Claro</span>
                        </label>
                        <label class="apar-modo">
                            <input type="radio" name="apariencia" value="oscuro" {{ $apariencia === 'oscuro' ? 'checked' : '' }} hidden>
                            <span class="apar-modo__card"><i class="fa-solid fa-moon"></i> Oscuro</span>
                        </label>
                    </div>
                </div>

                <div class="apar-seccion">
                    <span class="apar-label">Color principal</span>
                    <div class="apar-colores">
                        @foreach (config('psicocms.colores_dashboard') as $c)
                            <label class="apar-color" title="{{ $c['nombre'] }}">
                                <input type="radio" name="color" value="{{ $c['clave'] }}"
                                    data-primary="{{ $c['primary'] }}" data-dark="{{ $c['dark'] }}"
                                    {{ $c['clave'] === $colorActivo ? 'checked' : '' }} hidden>
                                <span class="apar-color__swatch" style="background: {{ $c['primary'] }};"><i class="fa-solid fa-check"></i></span>
                                <span class="apar-color__name">{{ $c['nombre'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal__actions">
                <button type="button" class="btn btn--ghost" data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-check"></i> Guardar apariencia</button>
            </div>
        </form>
    </div>
</div>
