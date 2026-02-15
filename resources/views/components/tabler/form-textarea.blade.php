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
    'type' => 'normal', // 'normal' | 'editor'
    'height' => 400,
    'plugins' => 'lists link image anchor searchreplace code fullscreen insertdatetime media table wordcount',
    'toolbar' => 'undo redo | blocks | bold italic table forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen',
])

@php
    $id = $attributes->get('id', $name);
    $value = old($name, $value);
@endphp

<div {{ $attributes->merge(['class' => 'mb-3 ' . ($type === 'editor' ? 'tinymce-container' : '')]) }}>
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
        {{ $attributes->except(['class', 'value', 'rows', 'type', 'height', 'plugins', 'toolbar']) }}
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

@if($type === 'editor')
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editorId = '{{ $id }}';
                const editorElement = document.getElementById(editorId);
                
                if (editorElement && window.loadHugeRTE) {
                    window.loadHugeRTE('#' + editorId, {
                        height: {{ $height }},
                        menubar: false,
                        statusbar: false,
                        plugins: '{{ $plugins }}',
                        toolbar: '{{ $toolbar }}',
                        skin: false,
                        content_css: false,
                        content_style: (window.hugerteContentCss || '') + ' body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }',
                        setup: function (editor) {
                            editor.on('change', function () {
                                editor.save();
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
@endif
