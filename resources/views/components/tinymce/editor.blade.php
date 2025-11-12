@props([
    'name' => 'content',
    'id' => 'tinymce-editor',
    'value' => '',
    'height' => 400,
    'plugins' => 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    'toolbar' => 'undo redo | blocks | ' .
               'bold italic forecolor | alignleft aligncenter ' .
               'alignright alignjustify | bullist numlist outdent indent | ' .
               'removeformat | help',
])

<div {{ $attributes->merge(['class' => 'tinymce-container']) }}>
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except(['id', 'name', 'value', 'height', 'plugins', 'toolbar']) }}
    >{{ old($name, $value) }}</textarea>
</div>

@push('scripts')
    <script src="{{ asset('assets-admin/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        // Initialize TinyMCE only if the element exists
        document.addEventListener('DOMContentLoaded', function() {
            const editorElement = document.getElementById('{{ $id }}');
            if (editorElement) {
                tinymce.init({
                    selector: '#{{ $id }}',
                    height: {{ $height }},
                    menubar: false,
                    license_key:'gpl',
                    plugins: '{{ $plugins }}',
                    toolbar: '{{ $toolbar }}',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                    init_instance_callback: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            }
        });
    </script>
@endpush
