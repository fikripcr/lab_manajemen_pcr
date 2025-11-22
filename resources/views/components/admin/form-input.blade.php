@props([
    'name', 
    'label', 
    'type' => 'text', 
    'required' => false, 
    'value' => old($name),
    'help' => null,
    'readonly' => false,
    'disabled' => false
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ $value }}"
        @if($required)required@endif
        @if($readonly)readonly@endif
        @if($disabled)disabled@endif
        {{ $attributes->except(['class', 'value'])->merge(['class' => '']) }}
    >
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>