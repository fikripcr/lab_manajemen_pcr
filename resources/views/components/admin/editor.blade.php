@props([
    'name' => 'content',
    'id' => 'hugerte-editor',
    'value' => '',
    'height' => 400,
])

<div {{ $attributes->merge(['class' => 'hugerte-container']) }}>
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except(['id', 'name', 'value', 'height']) }}
    >{{ old($name, $value) }}</textarea>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.loadHugeRTE) {
                window.loadHugeRTE('#{{ $id }}', {
                    height: {{ $height }},
                    menubar: false,
                    license_key: 'gpl',
                    plugins: 'lists link image anchor searchreplace code fullscreen insertdatetime media table wordcount',
                    toolbar: 'undo redo | blocks | bold italic table forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            } else {
                console.error('HugoRTE loader not found (window.loadHugeRTE is undefined)');
            }
        });
    </script>
@endpush
