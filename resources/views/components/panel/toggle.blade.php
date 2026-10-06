@props(['name', 'label' => null, 'checked' => false, 'value' => 1])

<label class="toggle">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" {{ $checked ? 'checked' : '' }} {{ $attributes }}>
    <span class="toggle__track"></span>
    @if ($label)
        <span class="toggle__label">{{ $label }}</span>
    @endif
</label>
