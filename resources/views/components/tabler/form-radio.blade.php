@props([
    'name',
    'label' => null,
    'value',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'description' => null,
    'class' => '',
    'inputClass' => '',
])

@php
    $inputId = $attributes->get('id') ?? $name . '_' . Str::slug($value);
    $isChecked = old($name, $checked) == $value || (old($name) === null && $checked);
    
    // Handle array naming for old()
    if (str_contains($name, '[')) {
        if (str_ends_with($name, '[]')) {
             $dotName = substr($name, 0, -2);
        } else {
             $dotName = str_replace(['[', ']'], ['.', ''], $name);
        }

        $oldValue = old($dotName);
        if (is_array($oldValue)) {
             $isChecked = in_array($value, $oldValue);
        } elseif ($oldValue !== null) {
             $isChecked = $oldValue == $value;
        }
    }
@endphp

<div class="mb-3 {{ $class }}">
    <label class="form-check">
        <input 
            type="radio" 
            class="form-check-input {{ $inputClass }} @error($name) is-invalid @enderror" 
            name="{{ $name }}" 
            value="{{ $value }}"
            id="{{ $inputId }}"
            @if($isChecked) checked @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge()->except(['class', 'label', 'value', 'checked', 'required', 'disabled', 'description', 'inputClass']) }}
        >
        <span class="form-check-label">
            {{ $label ?? $slot }}
            @if($required) <span class="text-danger">*</span> @endif
        </span>
        @if($description)
            <span class="form-check-description">
                {{ $description }}
            </span>
        @endif
    </label>
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
