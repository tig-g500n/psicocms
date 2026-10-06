@props(['id', 'titulo' => null])

<div class="modal" id="{{ $id }}" data-modal>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true">
        <button type="button" class="modal__close" data-modal-close aria-label="Cerrar">
            <i class="fa-solid fa-xmark"></i>
        </button>
        @if ($titulo)
            <h2 class="modal__title">{{ $titulo }}</h2>
        @endif
        <div class="modal__body">
            {{ $slot }}
        </div>
        @isset($acciones)
            <div class="modal__actions">
                {{ $acciones }}
            </div>
        @endisset
    </div>
</div>
