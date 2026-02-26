@extends('layouts.tabler.app')

@section('title', 'Kanban Board - ' . $project->project_name)
@section('pretitle', 'Project Management')

@section('header')
<x-tabler.page-header title="Kanban Board" :pretitle="$project->project_name">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button
                href="{{ route('projects.show', $project) }}"
                class="btn-secondary"
                icon="ti ti-arrow-left"
                text="Back to Dashboard"
            />
            <x-tabler.button
                href="javascript:void(0)"
                class="btn-primary ajax-modal-btn"
                data-url="{{ route('projects.tasks.create-modal', $project) }}"
                data-modal-title="Create New Task"
                icon="ti ti-plus"
                text="Add Task"
            />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.flash-message />

<div class="card">
    <div class="card-body p-0">
        <div id="fullKanban"></div>
    </div>
</div>
@endsection

{{-- CSS via resources/css/components/projects-kanban.css (bundled by Vite) --}}

@push('scripts')
<script type="module">
// jKanban logic ada di resources/js/helpers/projects-kanban.js (bundled + imported via tabler.js)
document.addEventListener('DOMContentLoaded', function() {
    window.initProjectsKanban({
        element: '#fullKanban',
        loadUrl: '{{ route('projects.tasks.kanban-data', $project) }}',
        moveUrl: '{{ route('projects.tasks.move', [$project, '__TASK_ID__']) }}',
        createUrl: '{{ route('projects.tasks.create-modal', $project) }}',
        widthBoard: '300px',
        boards: ['todo', 'in_progress', 'done']
    });
});
</script>
@endpush
