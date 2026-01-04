@props([
    'name' => 'content',
    'id' => 'tinymce-editor',
    'value' => '',
    'height' => 400,
    'plugins' => '  lists link image   anchor searchreplace  code fullscreen insertdatetime media table wordcount',
    'toolbar' => 'undo redo |  blocks | ' .
               'bold italic table forecolor | alignleft aligncenter ' .
               'alignright alignjustify | bullist numlist outdent indent | ' .
               'fullscreen',
])

<div {{ $attributes->merge(['class' => 'tinymce-container']) }}>
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except(['id', 'name', 'value', 'height', 'plugins', 'toolbar']) }}
    >{{ old($name, $value) }}</textarea>
</div>

@push('scripts')
    {{-- HugeRTE is bundled in sys.js via NPM --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editorId = '{{ $id }}';
            const editorElement = document.getElementById(editorId);
            
            if (editorElement) {
                hugerte.init({
                    selector: '#' + editorId,
                    height: {{ $height }},
                    menubar: false,
                    statusbar: false, /* Tabler cleaner look */
                    plugins: '{{ $plugins }}',
                    toolbar: '{{ $toolbar }}',
                    skin: false,       /* Bundled in sys.js */
                    content_css: false, /* Bundled in sys.js */
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
