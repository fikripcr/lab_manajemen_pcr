@props(['name', 'label', 'type' => 'text', 'required' => false, 'value' => old($name)])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <label for="{{ $name }}" class="form-label">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <input 
        type="{{ $type }}" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ $value }}"
        @if($required)required@endif
        {{ $attributes->except(['class'])->merge(['class' => '']) }}
    >
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>