@extends('layouts.sys.app')

@section('title', $pageTitle)

@push('css')
    <!-- Toast UI Editor CSS -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Documentation: {{ $page }}</h5>
            <div>
                <a href="{{ route('sys.documentation.show', $page) }}" class="btn btn-secondary me-2">
                    <i class="bx bx-arrow-back"></i> Back to View
                </a>
                <a href="{{ route('sys.documentation.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-book"></i> Documentation Index
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sys.documentation.update', $page) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="content" class="form-label">Documentation Content</label>
                    <div id="editor" style="height: 500px;"></div>
                    <textarea id="content" name="content" class="d-none">{{ old('content', $content) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sys.documentation.show', $page) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Toast UI Editor JS -->
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editorContainer = document.getElementById('editor');
            const contentTextarea = document.getElementById('content');

            // Initialize Toast UI Editor
            const editor = new toastui.Editor({
                el: editorContainer,
                previewStyle: 'vertical',
                height: '50vh',
                initialValue: contentTextarea.value,
                toolbarItems: [
                    ['heading', 'bold', 'italic', 'strike'],
                    ['hr', 'quote'],
                    ['ul', 'ol', 'task', 'indent', 'outdent'],
                    ['table', 'image', 'link'],
                    ['code', 'codeblock'],
                    ['scrollSync']
                ],
                usageStatistics: false
            });

            // Sync content back to textarea when form is submitted
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                contentTextarea.value = editor.getMarkdown();
            });
        });
    </script>
@endpush
@endsection
