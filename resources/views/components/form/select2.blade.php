@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select option',
    'multiple' => false,
    'required' => false
])

@php
    $id = $id ?? $name;
    // Check if associative array or list
    $isAssoc = array_keys($options) !== range(0, count($options) - 1);
@endphp

@if($label)
    <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $id }}">{{ $label }}</label>
@endif

<select 
    name="{{ $name . ($multiple ? '[]' : '') }}" 
    id="{{ $id }}" 
    {{ $attributes->merge(['class' => 'form-select select2-offline form-control']) }}
    data-placeholder="{{ $placeholder }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $required ? 'required' : '' }}
>
    @if(!$multiple)
        <option value=""></option> {{-- Placeholder requirement for Select2 --}}
    @endif

    @foreach($options as $key => $value)
        @php
            $optVal = $isAssoc ? $key : $value;
            $optLabel = $value;
            $isSelected = false;

            if (is_array($selected)) {
                $isSelected = in_array($optVal, $selected);
            } else {
                $isSelected = $selected == $optVal;
            }
        @endphp
        <option value="{{ $optVal }}" {{ $isSelected ? 'selected' : '' }}>{{ $optLabel }}</option>
    @endforeach
</select>
