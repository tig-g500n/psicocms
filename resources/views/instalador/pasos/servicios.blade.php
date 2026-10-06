@extends('instalador.layouts.app')

@php
    $servicios = old('servicios', $datos['servicios'] ?? []);
    $especialidades = old('especialidades', $datos['especialidades'] ?? []);
    $planes = old('planes', $datos['planes'] ?? []);
    $servicios = ! empty($servicios) ? array_values($servicios) : [['titulo' => '', 'descripcion' => '']];
    $especialidades = ! empty($especialidades) ? array_values($especialidades) : [['titulo' => '', 'descripcion' => '']];
    $planes = ! empty($planes) ? array_values($planes) : [['modalidad' => 'online', 'titulo' => '', 'precio' => '', 'caracteristicas' => '']];
@endphp

@section('contenido')
    <div class="inst__head">
        <h2 class="inst__title">Servicios, especialidades y precios</h2>
        <p class="inst__desc">Añade lo que ofreces. Puedes dejar filas vacías o completarlas después desde el panel.</p>
    </div>

    <form method="POST" action="{{ route('instalacion.servicios') }}" class="inst-form">
        @csrf

        {{-- Servicios --}}
        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-heart"></i> Servicios principales</h3>
            <div class="rep" data-repeatable data-group="servicios">
                @foreach ($servicios as $i => $fila)
                    <div class="rep-row">
                        <div class="rep-row__fields">
                            <input class="inst-field__input" type="text" name="servicios[{{ $i }}][titulo]" value="{{ $fila['titulo'] ?? '' }}" placeholder="Título del servicio">
                            <input class="inst-field__input" type="text" name="servicios[{{ $i }}][descripcion]" value="{{ $fila['descripcion'] ?? '' }}" placeholder="Descripción breve">
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                @endforeach
                <template data-template>
                    <div class="rep-row">
                        <div class="rep-row__fields">
                            <input class="inst-field__input" type="text" name="servicios[__INDEX__][titulo]" placeholder="Título del servicio">
                            <input class="inst-field__input" type="text" name="servicios[__INDEX__][descripcion]" placeholder="Descripción breve">
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
            </div>
            <button type="button" class="inst-btn inst-btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir servicio</button>
        </div>

        {{-- Especialidades --}}
        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-brain"></i> Especialidades</h3>
            <div class="rep" data-repeatable data-group="especialidades">
                @foreach ($especialidades as $i => $fila)
                    <div class="rep-row">
                        <div class="rep-row__fields">
                            <input class="inst-field__input" type="text" name="especialidades[{{ $i }}][titulo]" value="{{ $fila['titulo'] ?? '' }}" placeholder="Especialidad (ej. Ansiedad)">
                            <input class="inst-field__input" type="text" name="especialidades[{{ $i }}][descripcion]" value="{{ $fila['descripcion'] ?? '' }}" placeholder="Descripción (opcional)">
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                @endforeach
                <template data-template>
                    <div class="rep-row">
                        <div class="rep-row__fields">
                            <input class="inst-field__input" type="text" name="especialidades[__INDEX__][titulo]" placeholder="Especialidad (ej. Ansiedad)">
                            <input class="inst-field__input" type="text" name="especialidades[__INDEX__][descripcion]" placeholder="Descripción (opcional)">
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
            </div>
            <button type="button" class="inst-btn inst-btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir especialidad</button>
        </div>

        {{-- Planes y precios --}}
        <div class="inst-block">
            <h3 class="inst-block__title"><i class="fa-solid fa-tags"></i> Planes y precios</h3>
            <div class="rep" data-repeatable data-group="planes">
                @foreach ($planes as $i => $fila)
                    <div class="rep-row">
                        <div class="rep-row__fields rep-row__fields--plan">
                            <select class="inst-field__input" name="planes[{{ $i }}][modalidad]">
                                <option value="online" {{ ($fila['modalidad'] ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                                <option value="presencial" {{ ($fila['modalidad'] ?? '') === 'presencial' ? 'selected' : '' }}>Presencial</option>
                            </select>
                            <input class="inst-field__input" type="text" name="planes[{{ $i }}][titulo]" value="{{ $fila['titulo'] ?? '' }}" placeholder="Título del plan">
                            <input class="inst-field__input" type="text" name="planes[{{ $i }}][precio]" value="{{ $fila['precio'] ?? '' }}" placeholder="Precio (ej. $500 MXN)">
                            <textarea class="inst-field__input" name="planes[{{ $i }}][caracteristicas]" rows="3" placeholder="Qué incluye (una por línea)">{{ $fila['caracteristicas'] ?? '' }}</textarea>
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                @endforeach
                <template data-template>
                    <div class="rep-row">
                        <div class="rep-row__fields rep-row__fields--plan">
                            <select class="inst-field__input" name="planes[__INDEX__][modalidad]">
                                <option value="online">Online</option>
                                <option value="presencial">Presencial</option>
                            </select>
                            <input class="inst-field__input" type="text" name="planes[__INDEX__][titulo]" placeholder="Título del plan">
                            <input class="inst-field__input" type="text" name="planes[__INDEX__][precio]" placeholder="Precio (ej. $500 MXN)">
                            <textarea class="inst-field__input" name="planes[__INDEX__][caracteristicas]" rows="3" placeholder="Qué incluye (una por línea)"></textarea>
                        </div>
                        <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
            </div>
            <button type="button" class="inst-btn inst-btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir plan</button>
        </div>

        <div class="inst-form__actions">
            <a href="{{ route('instalacion.perfil') }}" class="inst-btn inst-btn--ghost"><i class="fa-solid fa-arrow-left"></i> Atrás</a>
            <button type="submit" class="inst-btn inst-btn--primary">Continuar <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/instalador/js/instalador.js') }}"></script>
@endpush
