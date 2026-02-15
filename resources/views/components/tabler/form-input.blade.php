@props([
    'name',
    'label' => null,
    'type' => 'text', // text, date, datetime, range, multiple, number, password, file, etc.
    'required' => false,
    'value' => null,
    'help' => null,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => null,
    'class' => '',
    'multiple' => false
])

@php
    $isDate = in_array($type, ['date', 'time', 'datetime', 'range', 'multiple']);
    $inputId = $attributes->get('id') ?? $name;
    
    // Classes for the input element
    $inputClasses = ['form-control'];
    if ($isDate) {
        $inputClasses[] = 'flatpickr-input';
    }
    if ($type === 'file') {
        $inputClasses[] = 'filepond-input';
    }
    if ($errors->has($name)) {
        $inputClasses[] = 'is-invalid';
    }

    // Determine the actual input type for the HTML element
    $htmlType = $type;
    if ($isDate) {
        $htmlType = 'text'; // Flatpickr works better on text inputs
    }
@endphp

<div class="mb-3 {{ $class }}">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">
            {{ $label }} @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    <div class="input-icon-container">
        <input
            type="{{ $htmlType }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => implode(' ', $inputClasses)])->except(['label', 'type', 'required', 'readonly', 'disabled', 'value']) }}
            @if($required) required @endif
            @if($readonly) readonly @endif
            @if($disabled) disabled @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($multiple) multiple @endif
            
            @if($isDate)
                data-flatpickr-type="{{ $type }}"
                @if($type === 'datetime') data-flatpickr-enable-time="true" @endif
                @if($type === 'range') data-flatpickr-mode="range" @endif
                @if($type === 'multiple') data-flatpickr-mode="multiple" @endif
            @endif

            @if($type === 'file')
                @if($attributes->has('accept')) data-accepted-file-types="{{ $attributes->get('accept') }}" @endif
                @if($multiple) data-allow-multiple="true" @endif
            @endif
        >
    </div>

    {{ $slot }}

    @if($type === 'password')
        @once
            @push('scripts')
                <script>
                    function togglePasswordVisibility(inputId, el) {
                        const input = document.getElementById(inputId);
                        const icon = el.querySelector('i');
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.replace('ti-eye-off', 'ti-eye');
                        } else {
                            input.type = 'password';
                            icon.classList.replace('ti-eye', 'ti-eye-off');
                        }
                    }
                </script>
            @endpush
        @endonce
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>