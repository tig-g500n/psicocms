@extends('panel.layouts.app')

@section('titulo', 'Información pública')

@push('estilos')
<link href="{{ asset('assets/dashboard/css/info-publica.css') }}" rel="stylesheet">
@endpush

@php
    $svs = old('servicios', $servicios->map(fn ($s) => ['titulo' => $s->titulo, 'descripcion' => $s->descripcion, 'icono' => $s->icono, 'activo' => $s->activo ? '1' : '0'])->all());
    $esps = old('especialidades', $especialidades->map(fn ($e) => ['titulo' => $e->titulo, 'descripcion' => $e->descripcion])->all());
    $plns = old('planes', $planes->map(fn ($p) => ['modalidad' => $p->modalidad, 'titulo' => $p->titulo, 'precio' => $p->precio, 'caracteristicas' => $p->caracteristicas])->all());
    $tab = in_array(request('tab'), ['perfil', 'servicios', 'especialidades', 'planes'], true) ? request('tab') : 'perfil';
    $iconosServicios = [
        'fa-heart', 'fa-brain', 'fa-hand-holding-heart', 'fa-comments', 'fa-comment-dots', 'fa-user-group',
        'fa-people-arrows', 'fa-hands-holding-child', 'fa-child', 'fa-baby', 'fa-face-smile', 'fa-face-grin-hearts',
        'fa-lightbulb', 'fa-puzzle-piece', 'fa-book-open', 'fa-graduation-cap', 'fa-leaf', 'fa-seedling',
        'fa-spa', 'fa-dove', 'fa-feather', 'fa-moon', 'fa-sun', 'fa-cloud-sun',
        'fa-shield-heart', 'fa-heart-pulse', 'fa-hand-holding-medical', 'fa-user-doctor', 'fa-couch', 'fa-handshake-angle',
        'fa-person-walking', 'fa-people-group', 'fa-scale-balanced', 'fa-clock', 'fa-video', 'fa-phone',
    ];
@endphp

@section('contenido')
    <x-panel.page-header titulo="Información pública" subtitulo="Edita los datos que se muestran en tu web (rellenados en el asistente)." />

    <div data-tabs>
        <div class="ip-tabs">
            <button type="button" class="ip-tab {{ $tab === 'perfil' ? 'is-active' : '' }}" data-tab="perfil"><i class="fa-solid fa-address-card"></i> Perfil</button>
            <button type="button" class="ip-tab {{ $tab === 'servicios' ? 'is-active' : '' }}" data-tab="servicios"><i class="fa-solid fa-hand-holding-heart"></i> Servicios</button>
            <button type="button" class="ip-tab {{ $tab === 'especialidades' ? 'is-active' : '' }}" data-tab="especialidades"><i class="fa-solid fa-brain"></i> Especialidades</button>
            <button type="button" class="ip-tab {{ $tab === 'planes' ? 'is-active' : '' }}" data-tab="planes"><i class="fa-solid fa-tags"></i> Planes y precios</button>
        </div>

        {{-- ===== Perfil ===== --}}
        <div class="ip-panel" data-panel="perfil" @if ($tab !== 'perfil') hidden @endif>
            <div class="card">
                <form method="POST" action="{{ route('panel.info-publica.perfil') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="campo">
                        <label class="campo__label">Foto pública</label>
                        <label class="ip-foto" for="foto">
                            <span class="ip-foto__preview" id="foto-preview">
                                @if ($perfil->foto)
                                    <img src="{{ asset($perfil->foto) }}" alt="Foto">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </span>
                            <span>
                                <strong>Cambiar foto</strong>
                                <div class="campo__hint">Preferiblemente sin fondo. JPG, PNG o WEBP, máx. 4 MB.</div>
                            </span>
                            <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp" hidden data-img-input="#foto-preview">
                        </label>
                    </div>

                    <div class="citas-form-grid">
                        <div class="campo">
                            <label class="campo__label" for="nombre">Nombre</label>
                            <input class="campo__input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $perfil->nombre) }}" required>
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="apellidos">Apellidos</label>
                            <input class="campo__input" type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $perfil->apellidos) }}" required>
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="num_colegiado">Cédula profesional</label>
                            <input class="campo__input" type="text" id="num_colegiado" name="num_colegiado" value="{{ old('num_colegiado', $perfil->num_colegiado) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="eslogan">Eslogan</label>
                            <input class="campo__input" type="text" id="eslogan" name="eslogan" value="{{ old('eslogan', $perfil->eslogan) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="telefono_citas">Teléfono para citas</label>
                            <input class="campo__input" type="text" id="telefono_citas" name="telefono_citas" value="{{ old('telefono_citas', $perfil->telefono_citas) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="email_citas">Email para citas</label>
                            <input class="campo__input" type="email" id="email_citas" name="email_citas" value="{{ old('email_citas', $perfil->email_citas) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="direccion">Dirección</label>
                            <input class="campo__input" type="text" id="direccion" name="direccion" value="{{ old('direccion', $perfil->direccion) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="lugar_consulta">Lugar de consulta</label>
                            <input class="campo__input" type="text" id="lugar_consulta" name="lugar_consulta" value="{{ old('lugar_consulta', $perfil->lugar_consulta) }}">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="mapa_lat">Mapa · Latitud</label>
                            <input class="campo__input" type="text" id="mapa_lat" name="mapa_lat" value="{{ old('mapa_lat', $perfil->mapa_lat) }}" placeholder="Ej. 40.4168">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="mapa_lng">Mapa · Longitud</label>
                            <input class="campo__input" type="text" id="mapa_lng" name="mapa_lng" value="{{ old('mapa_lng', $perfil->mapa_lng) }}" placeholder="Ej. -3.7038">
                        </div>
                    </div>

                    <div class="campo">
                        <label class="campo__label" for="sobre_mi">Sobre mí</label>
                        <x-panel.wysiwyg name="sobre_mi" :value="old('sobre_mi', $perfil->sobre_mi)" rows="8" />
                    </div>

                    <div class="ip-actions">
                        <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar perfil</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== Servicios ===== --}}
        <div class="ip-panel" data-panel="servicios" @if ($tab !== 'servicios') hidden @endif>
            <div class="card">
                <form method="POST" action="{{ route('panel.info-publica.servicios') }}">
                    @csrf
                    @method('PUT')
                    <div class="rep" data-repeatable>
                        @foreach ($svs as $i => $s)
                            <div class="rep-row">
                                <div class="rep-row__fields rep-row__fields--serv">
                                    <input class="campo__input" type="text" name="servicios[{{ $i }}][titulo]" value="{{ $s['titulo'] ?? '' }}" placeholder="Título">
                                    <input class="campo__input" type="text" name="servicios[{{ $i }}][descripcion]" value="{{ $s['descripcion'] ?? '' }}" placeholder="Descripción">
                                    <div class="icono-picker" data-icono-picker>
                                        <input type="hidden" name="servicios[{{ $i }}][icono]" value="{{ $s['icono'] ?? '' }}" data-icono-valor>
                                        <button type="button" class="campo__input icono-picker__btn" data-icono-toggle>
                                            <i class="fa-solid {{ $s['icono'] ?: 'fa-icons' }}" data-icono-preview></i>
                                            <span class="icono-picker__label">Icono</span>
                                            <i class="fa-solid fa-chevron-down icono-picker__chev"></i>
                                        </button>
                                        <div class="icono-picker__panel" data-icono-panel hidden>
                                            @foreach ($iconosServicios as $ic)
                                                <button type="button" class="icono-opcion" data-icono="{{ $ic }}" title="{{ $ic }}"><i class="fa-solid {{ $ic }}"></i></button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select class="campo__input" name="servicios[{{ $i }}][activo]">
                                        <option value="1" @selected(($s['activo'] ?? '1') === '1')>Visible</option>
                                        <option value="0" @selected(($s['activo'] ?? '1') === '0')>Oculto</option>
                                    </select>
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                        <template data-template>
                            <div class="rep-row">
                                <div class="rep-row__fields rep-row__fields--serv">
                                    <input class="campo__input" type="text" name="servicios[__INDEX__][titulo]" placeholder="Título">
                                    <input class="campo__input" type="text" name="servicios[__INDEX__][descripcion]" placeholder="Descripción">
                                    <div class="icono-picker" data-icono-picker>
                                        <input type="hidden" name="servicios[__INDEX__][icono]" value="" data-icono-valor>
                                        <button type="button" class="campo__input icono-picker__btn" data-icono-toggle>
                                            <i class="fa-solid fa-icons" data-icono-preview></i>
                                            <span class="icono-picker__label">Icono</span>
                                            <i class="fa-solid fa-chevron-down icono-picker__chev"></i>
                                        </button>
                                        <div class="icono-picker__panel" data-icono-panel hidden>
                                            @foreach ($iconosServicios as $ic)
                                                <button type="button" class="icono-opcion" data-icono="{{ $ic }}" title="{{ $ic }}"><i class="fa-solid {{ $ic }}"></i></button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select class="campo__input" name="servicios[__INDEX__][activo]">
                                        <option value="1">Visible</option>
                                        <option value="0">Oculto</option>
                                    </select>
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir servicio</button>
                    <div class="ip-actions">
                        <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar servicios</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== Especialidades ===== --}}
        <div class="ip-panel" data-panel="especialidades" @if ($tab !== 'especialidades') hidden @endif>
            <div class="card">
                <form method="POST" action="{{ route('panel.info-publica.especialidades') }}">
                    @csrf
                    @method('PUT')
                    <div class="rep" data-repeatable>
                        @foreach ($esps as $i => $e)
                            <div class="rep-row">
                                <div class="rep-row__fields">
                                    <input class="campo__input" type="text" name="especialidades[{{ $i }}][titulo]" value="{{ $e['titulo'] ?? '' }}" placeholder="Especialidad">
                                    <input class="campo__input" type="text" name="especialidades[{{ $i }}][descripcion]" value="{{ $e['descripcion'] ?? '' }}" placeholder="Descripción (opcional)">
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                        <template data-template>
                            <div class="rep-row">
                                <div class="rep-row__fields">
                                    <input class="campo__input" type="text" name="especialidades[__INDEX__][titulo]" placeholder="Especialidad">
                                    <input class="campo__input" type="text" name="especialidades[__INDEX__][descripcion]" placeholder="Descripción (opcional)">
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir especialidad</button>
                    <div class="ip-actions">
                        <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar especialidades</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== Planes ===== --}}
        <div class="ip-panel" data-panel="planes" @if ($tab !== 'planes') hidden @endif>
            <div class="card">
                <form method="POST" action="{{ route('panel.info-publica.planes') }}">
                    @csrf
                    @method('PUT')
                    <div class="rep" data-repeatable>
                        @foreach ($plns as $i => $p)
                            <div class="rep-row">
                                <div class="rep-row__fields rep-row__fields--plan">
                                    <select class="campo__input" name="planes[{{ $i }}][modalidad]">
                                        <option value="online" @selected(($p['modalidad'] ?? '') === 'online')>Online</option>
                                        <option value="presencial" @selected(($p['modalidad'] ?? '') === 'presencial')>Presencial</option>
                                    </select>
                                    <input class="campo__input" type="text" name="planes[{{ $i }}][titulo]" value="{{ $p['titulo'] ?? '' }}" placeholder="Título del plan">
                                    <input class="campo__input" type="text" name="planes[{{ $i }}][precio]" value="{{ $p['precio'] ?? '' }}" placeholder="Precio ($500 MXN)">
                                    <textarea class="campo__input" name="planes[{{ $i }}][caracteristicas]" rows="3" placeholder="Qué incluye (una por línea)">{{ $p['caracteristicas'] ?? '' }}</textarea>
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                        <template data-template>
                            <div class="rep-row">
                                <div class="rep-row__fields rep-row__fields--plan">
                                    <select class="campo__input" name="planes[__INDEX__][modalidad]">
                                        <option value="online">Online</option>
                                        <option value="presencial">Presencial</option>
                                    </select>
                                    <input class="campo__input" type="text" name="planes[__INDEX__][titulo]" placeholder="Título del plan">
                                    <input class="campo__input" type="text" name="planes[__INDEX__][precio]" placeholder="Precio ($500 MXN)">
                                    <textarea class="campo__input" name="planes[__INDEX__][caracteristicas]" rows="3" placeholder="Qué incluye (una por línea)"></textarea>
                                </div>
                                <button type="button" class="rep-row__remove" data-remove aria-label="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn--soft" data-add><i class="fa-solid fa-plus"></i> Añadir plan</button>
                    <div class="ip-actions">
                        <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Guardar planes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/dashboard/js/repetibles.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/icono-picker.js') }}"></script>
@endpush
