@props(['name', 'value' => '', 'id' => null, 'rows' => 10])

@php($idCampo = $id ?? $name)

<textarea
    {{ $attributes->merge(['class' => 'campo__input js-wysiwyg']) }}
    id="{{ $idCampo }}"
    name="{{ $name }}"
    rows="{{ $rows }}">{{ $value }}</textarea>

@once
    @push('estilos')
        <link href="{{ asset('assets/vendor/jodit/jodit.min.css') }}" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/vendor/jodit/jodit.min.js') }}"></script>
        <script src="{{ asset('assets/dashboard/js/wysiwyg.js') }}"></script>
    @endpush
@endonce
