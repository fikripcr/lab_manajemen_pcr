@props([
    'name', 
    'label', 
    'value' => old($name),
    'rows' => 3,
    'required' => false,
    'help' => null,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => ''
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <textarea 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required)required@endif
        @if($readonly)readonly@endif
        @if($disabled)disabled@endif
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
