@props([
    'name' => null, 
    'label' => null,
    'id' => null,
    'options' => [], 
    'selected' => null, 
    'placeholder' => 'Pilih...',
    'required' => false,
    'help' => null,
    'disabled' => false,
    'multiple' => false,
    'class' => '',
    'type' => 'normal' // 'normal' | 'select2'
])

@php
    $id = $id ?? $name;
    // $attributes = $attributes->merge(['class' => 'mb-3']);
    $selectClasses = 'form-select ' . ($type === 'select2' ? 'select2-offline' : '') . ' ' . ($errors->has($name) ? 'is-invalid' : '');
@endphp

<div {{ $attributes->only('class') }} {{ $class }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <select 
        class="{{ $selectClasses }}" 
        id="{{ $id }}" 
        name="{{ $name . ($multiple ? '[]' : '') }}" 
        @if($disabled) disabled="disabled"@endif
        @if($multiple) multiple="multiple"@endif
        {{ $attributes->except(['class', 'options', 'selected', 'id']) }}
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $key => $value)
            @php
                $val = is_object($value) ? ($value->id ?? $key) : $key;
                $text = is_object($value) ? ($value->name ?? $value) : $value;
                $is_selected = is_array($selected) ? in_array($val, $selected) : $selected == $val;
            @endphp
            <option value="{{ $val }}" {{ $is_selected ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach

        {{ $slot }}
    </select>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
