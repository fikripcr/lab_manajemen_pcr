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
    'type' => 'text',
])

@php
    $id = $attributes->get('id', $name);
    $value = old($name, $value);
    
    $textareaClasses = ['form-control'];
    if ($type === 'editor') {
        $textareaClasses[] = 'huge-editor';
    }
    if ($errors->has($name)) {
        $textareaClasses[] = 'is-invalid';
    }
@endphp

<div {{ $attributes->except(['id', 'name', 'value', 'rows', 'placeholder', 'required', 'readonly', 'disabled', 'type'])->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {!! $label !!} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <textarea 
        class="{{ implode(' ', $textareaClasses) }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($disabled) disabled="true"@endif
        @if($readonly) readonly="true"@endif
        {{ $attributes->whereStartsWith('data-') }}
    >{!! $value ?? $slot !!}</textarea>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

