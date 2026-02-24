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

@push('styles')
{{-- jKanban CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jkanban@1.3.1/dist/jkanban.min.css">
<style>
    #fullKanban {
        overflow-x: auto;
        padding: 20px;
        min-height: calc(100vh - 250px);
    }
    
    .kanban-container {
        display: flex !important;
        align-items: flex-start !important;
        width: max-content !important;
    }
    
    .kanban-board {
        background-color: #f1f5f9 !important;
        border-radius: 8px !important;
        margin-right: 20px !important;
        width: 300px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .kanban-board {
        background-color: #1e293b !important;
    }
    
    .kanban-board-header {
        padding: 15px !important;
        border-radius: 8px 8px 0 0 !important;
    }
    
    .kanban-board-header .kanban-title-board {
        font-weight: 700 !important;
        font-size: 1rem !important;
        color: #1e293b !important;
    }
    
    [data-bs-theme="dark"] .kanban-board-header .kanban-title-board {
        color: #f8fafc !important;
    }
    
    .kanban-board[data-id="todo"] .kanban-board-header { border-top: 4px solid var(--tblr-blue); }
    .kanban-board[data-id="in_progress"] .kanban-board-header { border-top: 4px solid var(--tblr-yellow); }
    .kanban-board[data-id="review"] .kanban-board-header { border-top: 4px solid var(--tblr-orange); }
    .kanban-board[data-id="done"] .kanban-board-header { border-top: 4px solid var(--tblr-green); }
    
    .kanban-item {
        background: white !important;
        border-radius: 8px !important;
        padding: 15px !important;
        margin-bottom: 12px !important;
        transition: all 0.2s ease !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    
    [data-bs-theme="dark"] .kanban-item {
        background: #2b3a52 !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    .kanban-item:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .kanban-drag {
        padding: 5px 10px !important;
        min-height: 200px !important;
    }
    
    .kanban-board-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 15px 20px !important;
        background: transparent !important;
    }

    .kanban-title-board {
        font-weight: 600 !important;
        font-size: 1.1rem !important;
        color: var(--tblr-emphasis-color) !important;
        margin: 0 !important;
    }

    .kanban-title-button {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: var(--tblr-bg-surface-secondary) !important;
        border: 1px solid var(--tblr-border-color) !important;
        color: var(--tblr-muted) !important;
        font-size: 1.5rem !important;
        font-weight: 400 !important;
        line-height: 1 !important;
        cursor: pointer !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .kanban-title-button:hover {
        background: var(--tblr-primary) !important;
        border-color: var(--tblr-primary) !important;
        color: white !important;
        transform: scale(1.1) rotate(90deg) !important;
        box-shadow: 0 4px 12px rgba(var(--tblr-primary-rgb), 0.3) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jkanban@1.3.1/dist/jkanban.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Kanban Instance globally
    let kanban;

    // Load tasks data and initialize/refresh board
    function loadKanbanData() {
        axios.get('{{ route('projects.tasks.kanban-data', $project) }}')
            .then(response => {
                if (response.data.success) {
                    const tasks = response.data.data;
                    
                    if (kanban) {
                        // Update existing boards
                        ['todo', 'in_progress', 'done'].forEach(boardId => {
                            kanban.removeAllItems(boardId);
                            if (tasks[boardId]) {
                                tasks[boardId].forEach(item => {
                                    kanban.addElement(boardId, item);
                                });
                            }
                        });
                    } else {
                        // Initial initialization
                        initKanbanBoard(tasks);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching Kanban data:', error);
                showErrorMessage('Failed to refresh Kanban board');
            });
    }

    function initKanbanBoard(tasks) {
        kanban = new jKanban({
            element: '#fullKanban',
            gutter: '15px',
            widthBoard: '300px',
            dragItems: true,
            itemAddOptions: {
                enabled: true,
                content: '+',
                class: 'kanban-title-button',
                footer: false
            },
            boards: [
                { id: 'todo', title: 'To Do', item: tasks.todo || [] },
                { id: 'in_progress', title: 'In Progress', item: tasks.in_progress || [] },
                { id: 'done', title: 'Done', item: tasks.done || [] }
            ],
            dropEl: function(el, target, source, sibling) {
                const taskId = el.dataset.eid;
                const newStatus = target.parentElement.dataset.id;
                
                axios.post('{{ route('projects.tasks.move', [$project, '__ID__']) }}'.replace('__ID__', taskId), {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                }).then(() => {
                    showSuccessMessage('Task moved to ' + newStatus.replace('_', ' '));
                }).catch((error) => {
                    console.error('Error moving task:', error);
                    showErrorMessage('Failed to move task');
                    loadKanbanData(); // Rollback
                });
            },
            buttonClick: function(el, boardId) {
                var modalUrl = '{{ route('projects.tasks.create-modal', $project) }}?status=' + boardId;
                openAjaxModal(modalUrl, 'Create New Task');
            }
        });
    }

    // Call initial load
    loadKanbanData();

    // Listener for AJAX form success
    document.addEventListener('ajax-form:success', function(e) {
        loadKanbanData();
    });
    
    function showNotification(type, message) {
        if (type === 'success') {
            showSuccessMessage(message);
        } else {
            showErrorMessage(message);
        }
    }
    
    function openAjaxModal(url, title) {
        // Implementation using existing modal helpers
        window.openAjaxModal(url, title);
    }
});
</script>
@endpush
