@extends('layouts.tabler.app')

@section('title', $pageTitle)

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Documentation">
    <x-slot:actions>
        <div class="btn-group">
            <x-tabler.button type="back" :href="route('sys.documentation.show', ['path' => $path])" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb -->
        <x-tabler.card class="mb-3">
            <x-tabler.card-body>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrows mb-0">
                        @foreach($breadcrumb as $item)
                            @if($item['url'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </x-tabler.card-body>
        </x-tabler.card>

        <form action="{{ route('sys.documentation.update', ['path' => $path]) }}" method="POST" class="ajax-form">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Editor -->
                <div class="col-lg-9">
                    <x-tabler.card>
                        <x-tabler.card-header>
                            <h4 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 5h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" /><path d="M14 11v6a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-6" /><path d="M5 11v6a2 2 0 0 0 2 2h2" /><path d="M5 7h4" /><path d="M5 9v8" /></svg>
                                Markdown Editor
                            </h4>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-preview" title="Toggle Preview (Ctrl+P)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M9 9l6 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                                    Preview
                                </button>
                            </div>
                        </x-tabler.card-header>
                        <x-tabler.card-body>
                            <!-- Toast UI Editor Container -->
                            <div id="toast-editor-wrapper" class="mb-3"></div>
                            
                            <!-- Hidden textarea for form submission -->
                            <textarea name="content" id="content-textarea" class="d-none">{{ old('content', $content) }}</textarea>
                            
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </x-tabler.card-body>
                        <x-tabler.card-footer>
                            <div class="d-flex gap-2">
                                <x-tabler.button type="submit" color="primary" icon="ti ti-check" text="Save Changes" />
                                <x-tabler.button type="button" :href="route('sys.documentation.show', ['path' => $path])" color="secondary" icon="ti ti-x" text="Cancel" />
                            </div>
                        </x-tabler.card-footer>
                    </x-tabler.card>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- File Info -->
                    <x-tabler.card class="mb-3">
                        <x-tabler.card-header title="File Information" icon="ti ti-info-circle" />
                        <x-tabler.card-body>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Filename</label>
                                <div class="text-monospace small">{{ $doc['filename'] }}.md</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Path</label>
                                <div class="text-monospace small">{{ $doc['relative_path'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Category</label>
                                <div>
                                    <span class="badge bg-primary-lt">{{ $doc['category'] }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Last Updated</label>
                                <div class="small">{{ formatTanggalIndo($doc['lastUpdated']) }}</div>
                            </div>
                            <div>
                                <label class="form-label small text-muted">File Size</label>
                                <div class="small">{{ number_format($doc['size'] / 1024, 2) }} KB</div>
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>

                    <!-- Markdown Guide -->
                    <x-tabler.card>
                        <x-tabler.card-header title="Markdown Guide" icon="ti ti-book" />
                        <x-tabler.card-body class="p-0">
                            <div class="list-group list-group-flush small">
                                <div class="list-group-item px-3 py-2">
                                    <code># Heading 1</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>## Heading 2</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>**bold** text</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>*italic* text</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>[link](url)</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>![image](url)</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>- list item</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>> quote</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>```code```</code>
                                </div>
                                <div class="list-group-item px-3 py-2">
                                    <code>| table |</code>
                                </div>
                            </div>
                        </x-tabler.card-body>
                        <x-tabler.card-footer>
                            <a href="https://www.markdownguide.org/cheat-sheet/" target="_blank" class="btn btn-sm btn-link">
                                Full Guide <i class="ti ti-external-link ms-1"></i>
                            </a>
                        </x-tabler.card-footer>
                    </x-tabler.card>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ Vite::asset('resources/assets/sys/css/documentation.css') }}">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const contentTextarea = document.getElementById('content-textarea');
        const toggleBtn = document.getElementById('toggle-preview');
        let editor = null;
        let isPreviewMode = false;

        // Initialize Toast UI Editor
        try {
            if (typeof window.initToastEditor === 'function') {
                editor = await window.initToastEditor('#toast-editor-wrapper', {
                    height: '600px',
                    initialEditType: 'markdown',
                    previewStyle: 'vertical',
                    theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'default',
                    initialValue: contentTextarea.value,
                    usageStatistics: false,
                    hooks: {
                        // Sync content to hidden textarea on change
                        change: () => {
                            if (editor) {
                                contentTextarea.value = editor.getMarkdown();
                            }
                        }
                    }
                });
                
                console.log('Toast UI Editor initialized successfully');
            } else {
                console.error('initToastEditor function not found. Make sure tabler.js is loaded.');
            }
        } catch (error) {
            console.error('Failed to initialize Toast UI Editor:', error);
        }

        // Toggle Preview Mode
        toggleBtn.addEventListener('click', function() {
            if (!editor) {
                alert('Editor belum siap, silakan tunggu beberapa saat');
                return;
            }
            
            isPreviewMode = !isPreviewMode;
            
            if (isPreviewMode) {
                // Switch to preview
                editor.changePreviewStyle('preview');
                toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 5h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" /><path d="M14 11v6a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-6" /><path d="M5 11v6a2 2 0 0 0 2 2h2" /><path d="M5 7h4" /><path d="M5 9v8" /></svg> Edit';
                toggleBtn.classList.replace('btn-outline-primary', 'btn-primary');
            } else {
                // Switch to edit
                editor.changePreviewStyle('tab');
                toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M9 9l6 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg> Preview';
                toggleBtn.classList.replace('btn-primary', 'btn-outline-primary');
            }
        });

        // Keyboard shortcut: Ctrl+P to toggle preview
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                toggleBtn.click();
            }
        });

        // Auto-save draft to localStorage
        const draftKey = 'doc_draft_' + '{{ $path }}';
        const autoSaveInterval = 30000; // 30 seconds

        // Load draft on page load
        const draft = localStorage.getItem(draftKey);
        if (draft && draft !== contentTextarea.value) {
            if (confirm('There is a saved draft. Would you like to load it?')) {
                if (editor) {
                    editor.setMarkdown(draft);
                } else {
                    contentTextarea.value = draft;
                }
            }
        }

        // Auto-save draft periodically
        setInterval(() => {
            if (editor) {
                const content = editor.getMarkdown();
                localStorage.setItem(draftKey, content);
            }
        }, autoSaveInterval);

        // Clear draft on successful save
        document.querySelector('form').addEventListener('submit', function() {
            // Ensure latest content is in textarea
            if (editor) {
                contentTextarea.value = editor.getMarkdown();
            }
            
            // Clear draft after short delay
            setTimeout(() => {
                localStorage.removeItem(draftKey);
            }, 1000);
        });
    });
</script>
@endpush
