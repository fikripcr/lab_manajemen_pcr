@props([
    'name', 
    'label' => null, 
    'value' => null,
    'rows' => 3,
    'required' => false,
    'help' => null,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => '',
    'placeholder' => '',
])

@php
    $id = $attributes->get('id', $name);
    $value = old($name, $value);
@endphp

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <textarea 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required="true"@endif
        @if($disabled) disabled="true"@endif
        @if($readonly) readonly="true"@endif
        {{ $attributes->except(['class', 'value', 'rows']) }}
    >{{ $value }}</textarea>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

