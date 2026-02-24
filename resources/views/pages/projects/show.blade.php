@extends('layouts.tabler.app')

@section('title', $project->project_name)
@section('pretitle', 'Project Dashboard')

@section('header')
<x-tabler.page-header :title="$project->project_name" pretitle="Overview">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button 
                href="{{ route('projects.kanban', $project) }}" 
                class="btn-azure" 
                icon="ti ti-kanban" 
                text="Full Kanban" 
            />
            <x-tabler.button 
                href="{{ route('projects.edit', $project) }}" 
                class="btn-primary" 
                icon="ti ti-edit" 
                text="Edit Project" 
            />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.flash-message />

{{-- Project Info Cards --}}
<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Tasks</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['total_tasks'] }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Completed</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['completed_tasks'] }}</div>
                    <div class="ms-auto text-muted">
                        {{ number_format($statistics['progress_percentage'], 1) }}%
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: {{ $statistics['progress_percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">In Progress</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['in_progress_tasks'] }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-warning" style="width: {{ $statistics['total_tasks'] > 0 ? ($statistics['in_progress_tasks'] / $statistics['total_tasks'] * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Cost</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Segmented Control Tabs --}}
<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills card-header-pills" role="tablist" id="projectTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#project-overview" role="tab" data-tab-id="overview">
                    <i class="ti ti-layout-dashboard me-2"></i>Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-tasks" role="tab" data-tab-id="tasks">
                    <i class="ti ti-checklist me-2"></i>Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-team" role="tab" data-tab-id="team">
                    <i class="ti ti-users me-2"></i>Team
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-cost" role="tab" data-tab-id="cost">
                    <i class="ti ti-cash me-2"></i>Cost
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            
            {{-- Overview Tab --}}
            <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-3">Project Information</h4>
                        <div class="mb-3">
                            <label class="form-label text-muted">Project Name</label>
                            <div class="h4">{{ $project->project_name }}</div>
                        </div>

                        @if($project->project_desc)
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $project->project_desc }}</p>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Start Date</label>
                                    <div><i class="ti ti-calendar me-1"></i> {{ formatTanggalIndo($project->start_date) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">End Date</label>
                                    <div><i class="ti ti-calendar me-1"></i> {{ formatTanggalIndo($project->end_date) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div>
                                <span class="badge {{ $project->status_badge_class }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Agile Mode</label>
                            <div>
                                @if($project->is_agile)
                                    <span class="badge bg-success-lt">Enabled</span>
                                @else
                                    <span class="badge bg-secondary-lt">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <h4 class="mb-3">Quick Stats</h4>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Task Completion</span>
                                <span class="text-muted">{{ $statistics['completed_tasks'] }}/{{ $statistics['total_tasks'] }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ $statistics['progress_percentage'] }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">To Do</span>
                                <span class="badge bg-blue-lt">{{ $statistics['todo_tasks'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">In Progress</span>
                                <span class="badge bg-yellow-lt">{{ $statistics['in_progress_tasks'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Review</span>
                                <span class="badge bg-orange-lt">{{ $statistics['review_tasks'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Done</span>
                                <span class="badge bg-green-lt">{{ $statistics['completed_tasks'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tasks Tab (jKanban) --}}
            <div class="tab-pane fade" id="project-tasks" role="tabpanel">
                <div id="taskKanban"></div>
            </div>

            {{-- Team Tab --}}
            <div class="tab-pane fade" id="project-team" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Project Team</h4>
                    <x-tabler.button 
                        href="javascript:void(0)" 
                        class="btn-primary btn-sm ajax-modal-btn" 
                        data-url="{{ route('projects.members.create-modal', $project) }}"
                        data-modal-title="Add Team Member"
                        icon="ti ti-plus" 
                        text="Add Member" 
                    />
                </div>
                @if($project->members->count() > 0)
                <div class="row row-cards">
                    @foreach($project->members as $member)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3">
                                        {{ substr(($member->user->name ?? 'U'), 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $member->user->name ?? 'Unknown' }}</div>
                                        <div class="text-muted small">
                                            @if($member->alias_position)
                                                {{ $member->alias_position }}
                                            @else
                                                {{ ucfirst($member->role) }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge {{ $member->role_badge_class }} me-2">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon border-0 shadow-none text-muted" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item text-danger ajax-delete" 
                                               href="javascript:void(0)" 
                                               data-url="{{ route('projects.members.destroy', [$project, $member]) }}"
                                               data-title="Remove member: {{ $member->user->name }}?">
                                                <i class="ti ti-trash me-2"></i>Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <x-tabler.empty-state 
                    title="No Team Members" 
                    message="Add team members to collaborate on this project"
                    icon="ti ti-users"
                />
                @endif
            </div>

            {{-- Cost Tab --}}
            <div class="tab-pane fade" id="project-cost" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Project Costs</h4>
                    <x-tabler.button 
                        href="javascript:void(0)" 
                        class="btn-primary btn-sm ajax-modal-btn" 
                        data-url="{{ route('projects.costs.create-modal', $project) }}"
                        data-modal-title="Record New Cost"
                        icon="ti ti-plus" 
                        text="Add Cost" 
                    />
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-muted">Total Cost</div>
                                        <div class="h2 mb-0">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
                                    </div>
                                    <i class="ti ti-cash text-primary fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-muted">Approved Costs</div>
                                        <div class="h2 mb-0">Rp {{ number_format($project->costs()->where('approval_status', 'approved')->sum('amount'), 0, ',', '.') }}</div>
                                    </div>
                                    <i class="ti ti-check text-success fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($project->costs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->costs->sortByDesc('created_at') as $cost)
                            <tr>
                                <td>{{ formatTanggalIndo($cost->cost_date) }}</td>
                                <td>{{ $cost->cost_desc }}</td>
                                <td>
                                    <span class="badge bg-{{ $cost->cost_type === 'in_cash' ? 'blue' : 'green' }}-lt">
                                        {{ str_replace('_', ' ', $cost->cost_type) }}
                                    </span>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($cost->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $cost->approval_status_badge_class }}">
                                        {{ ucfirst($cost->approval_status) }}
                                    </span>
                                </td>
                                <td>{{ $cost->author->name ?? 'Unknown' }}</td>
                                <td class="text-center">
                                    <div class="btn-list justify-content-center">
                                        <x-tabler.button 
                                            href="javascript:void(0)" 
                                            class="btn-icon btn-sm ajax-modal-btn" 
                                            data-url="{{ route('projects.costs.edit-modal', [$project, $cost]) }}"
                                            data-modal-title="Edit Cost"
                                            icon="ti ti-edit"
                                            text=""
                                        />
                                        <a href="javascript:void(0)" 
                                           class="btn btn-icon btn-sm btn-outline-danger ajax-delete" 
                                           data-url="{{ route('projects.costs.destroy', [$project, $cost]) }}"
                                           data-title="Delete this cost record?">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <x-tabler.empty-state 
                    title="No Costs Recorded" 
                    message="Add costs to track project expenses"
                    icon="ti ti-cash"
                />
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- jKanban CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jkanban@1.3.1/dist/jkanban.min.css">
<style>
    #taskKanban {
        overflow-x: auto;
    }
    
    .kanban-container {
        background-color: #f8f9fa !important;
        padding: 20px !important;
        border-radius: 8px;
        width: max-content !important;
    }
    
    [data-bs-theme="dark"] .kanban-container {
        background-color: #1a2234 !important;
    }
    
    .kanban-board {
        background-color: #e9ecef !important;
        border-radius: 8px;
        margin-right: 16px !important;
    }
    
    [data-bs-theme="dark"] .kanban-board {
        background-color: #243046 !important;
    }
    
    .kanban-board[data-type="todo"] .kanban-board-header {
        background-color: #e7f5ff !important;
    }
    
    .kanban-board[data-type="in_progress"] .kanban-board-header {
        background-color: #fff9db !important;
    }
    
    .kanban-board[data-type="review"] .kanban-board-header {
        background-color: #fff0f0 !important;
    }
    
    .kanban-board[data-type="done"] .kanban-board-header {
        background-color: #ebfbee !important;
    }
    
    .kanban-board-header {
        border-radius: 8px 8px 0 0 !important;
        padding: 12px !important;
    }
    
    .kanban-board-header h3 {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        margin: 0 !important;
    }
    
    .kanban-title-button {
        cursor: pointer;
        background: transparent;
        border: none;
        color: var(--tblr-muted);
        padding: 0;
        margin-left: 8px;
    }
    
    .kanban-title-button:hover {
        color: var(--tblr-primary);
    }
    
    .kanban-item {
        background: white !important;
        border-radius: 6px !important;
        padding: 12px !important;
        margin-bottom: 8px !important;
        cursor: grab !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }
    
    [data-bs-theme="dark"] .kanban-item {
        background: #2b3a52 !important;
    }
    
    .kanban-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
    
    .kanban-item:last-child {
        margin-bottom: 0 !important;
    }
    
    .kanban-add-btn {
        background: transparent !important;
        border: 2px dashed var(--tblr-border-color) !important;
        color: var(--tblr-muted) !important;
        padding: 8px 16px !important;
        margin: 8px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        width: calc(100% - 16px) !important;
        text-align: center !important;
        transition: all 0.2s !important;
    }
    
    .kanban-add-btn:hover {
        border-color: var(--tblr-primary) !important;
        color: var(--tblr-primary) !important;
        background: rgba(var(--tblr-primary-rgb), 0.05) !important;
    }
</style>
@endpush

@push('scripts')
{{-- jKanban JS --}}
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
            element: '#taskKanban',
            gutter: '16px',
            widthBoard: '280px',
            dragItems: true,
            itemAddOptions: {
                enabled: true,
                content: '+',
                class: 'kanban-title-button btn btn-sm btn-outline-secondary',
                footer: false
            },
            boards: [
                { id: 'todo', title: 'To Do', item: tasks.todo || [] },
                { id: 'in_progress', title: 'In Progress', item: tasks.in_progress || [] },
                { id: 'done', title: 'Done', item: tasks.done || [] }
            ],
            dropEl: function(el, target, source, sibling) {
                var taskId = el.getAttribute('data-eid');
                var newStatus = target.parentElement.getAttribute('data-id');
                
                axios.post('{{ route('projects.tasks.move', [$project, '__TASK_ID__']) }}'.replace('__TASK_ID__', taskId), {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                })
                .then(function(response) {
                    showSuccessMessage('Task moved to ' + newStatus.replace('_', ' '));
                })
                .catch(function(error) {
                    console.error('Error moving task:', error);
                    showErrorMessage('Failed to move task');
                    loadKanbanData(); // Rollback UI
                });
            },
            buttonClick: function(el, boardId) {
                var modalUrl = '{{ route('projects.tasks.create-modal', $project) }}?status=' + boardId;
                openAjaxModal(modalUrl, 'Create New Task');
            }
        });
    }

    // Initial load
    loadKanbanData();

    // Tab persistence
    const lastActiveTab = localStorage.getItem('project_active_tab_{{ $project->project_id }}');
    if (lastActiveTab) {
        const tabEl = document.querySelector(`a[data-tab-id="${lastActiveTab}"]`);
        if (tabEl) {
            new bootstrap.Tab(tabEl).show();
        }
    }

    document.querySelectorAll('#projectTabs a[data-bs-toggle="tab"]').forEach(tabLink => {
        tabLink.addEventListener('shown.bs.tab', function(e) {
            const tabId = e.target.getAttribute('data-tab-id');
            localStorage.setItem('project_active_tab_{{ $project->project_id }}', tabId);
        });
    });
    
    // Show notification toast
    function showNotification(type, message) {
        if (type === 'success') {
            showSuccessMessage(message);
        } else {
            showErrorMessage(message);
        }
    }
    
    // Open AJAX modal helper
    function openAjaxModal(url, title) {
        const modal = document.getElementById('modalAction');
        const modalContent = document.getElementById('modalContent');
        
        axios.get(url)
            .then(response => {
                modalContent.innerHTML = response.data;
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            })
            .catch(error => {
                console.error('Error loading modal:', error);
                showNotification('error', 'Failed to load modal');
            });
    }
    
    // Handle AJAX form success
    document.addEventListener('ajax-form:success', function(e) {
        // Check if response has redirect with #tasks
        if (e.detail && e.detail.response) {
            // If it's a task related action, refresh kanban instead of reload
            loadKanbanData();
            
            if (e.detail.response.redirect && e.detail.response.redirect.includes('#tasks')) {
                // Switch to tasks tab
                const tasksTab = document.querySelector('a[href="#project-tasks"]');
                if (tasksTab) {
                    new bootstrap.Tab(tasksTab).show();
                }
            }
        }
    });
    
    // Also support jQuery event (for backward compatibility)
    $(document).on('ajax-form:success', function(e, responseData, form) {
        if (responseData && responseData.redirect && responseData.redirect.includes('#tasks')) {
            const tasksTab = document.querySelector('a[href="#project-tasks"]');
            if (tasksTab) {
                new bootstrap.Tab(tasksTab).show();
            }
            setTimeout(() => location.reload(), 500);
        }
    });
});
</script>
@endpush
