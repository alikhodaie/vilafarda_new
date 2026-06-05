@php
    $inputId = 'filter_option_' . ($optionsIdPrefix ?? 'default') . '_' . $option->id;
    $isChecked = in_array((int) $option->id, $selectedOptionsList ?? [], true);
@endphp

<div class="form-check homes-filter-option-item {{ $isChecked ? 'is-checked' : '' }}">
    <input
        class="form-check-input"
        type="checkbox"
        name="options[]"
        value="{{ $option->id }}"
        id="{{ $inputId }}"
        {{ $isChecked ? 'checked' : '' }}
    >
    <label class="form-check-label" for="{{ $inputId }}">
        <x-option-icon :option="$option" :size="18" iconClass="homes-filter-option-item__icon" imgClass="homes-filter-option-item__icon" />
        <span class="homes-filter-option-item__title">{{ $option->title }}</span>
    </label>
</div>
