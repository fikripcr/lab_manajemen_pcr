@props([
    'name', 
    'label' => null,
    'options' => [], 
    'selected' => null, 
    'placeholder' => 'Pilih...',
    'required' => false,
    'help' => null,
    'disabled' => false,
    'multiple' => false,
    'type' => 'normal' // 'normal' | 'select2'
])

@php
    $attributes = $attributes->merge(['class' => 'mb-3']);
    $selectClasses = 'form-select ' . ($type === 'select2' ? 'select2-offline' : '') . ' ' . ($errors->has($name) ? 'is-invalid' : '');
@endphp

<div {{ $attributes }}>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <select 
        class="{{ $selectClasses }}" 
        id="{{ $name }}" 
        name="{{ $name . ($multiple ? '[]' : '') }}" 
        @if($required) required="true"@endif
        @if($disabled) disabled="true"@endif
        @if($multiple) multiple="true"@endif
        {{ $attributes->except(['class', 'options', 'selected']) }}
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $key => $value)
            @if(is_object($value))
                 <option value="{{ $value->id ?? $key }}" {{ (is_array($selected) ? in_array($value->id ?? $key, $selected) : $selected == ($value->id ?? $key)) ? 'selected' : '' }}>
                    {{ $value->name ?? $value }}
                 </option>
            @else
                <option value="{{ $key }}" {{ (is_array($selected) ? in_array($key, $selected) : $selected == $key) ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endif
        @endforeach

        {{ $slot }}
    </select>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
