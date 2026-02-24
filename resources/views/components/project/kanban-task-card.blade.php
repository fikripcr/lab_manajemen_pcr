@props(['task'])

<div class="kanban-card-content" data-task-id="{{ $task->encrypted_project_task_id }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="h4 mb-0 text-wrap">{{ $task->task_title }}</div>
        <div class="dropdown">
            <a href="#" class="btn-action text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-dots-vertical" style="font-size: 1.1rem;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="javascript:void(0)" onclick="openAjaxModal('{{ route('projects.tasks.edit-modal', [$task->project, $task]) }}', 'Edit Task')">
                    <i class="ti ti-edit me-2"></i> Edit Task
                </a>
                <a class="dropdown-item text-danger ajax-delete" 
                   href="javascript:void(0)" 
                   data-url="{{ route('projects.tasks.destroy', [$task->project, $task]) }}"
                   data-title="Delete Task"
                   data-text="Are you sure you want to delete this task?">
                    <i class="ti ti-trash me-2"></i> Delete
                </a>
            </div>
        </div>
    </div>
    
    @if($task->task_desc)
    <div class="text-muted small mb-2 text-wrap">{{ Str::limit($task->task_desc, 80) }}</div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center">
        <span class="badge badge-sm bg-{{ $task->priority_badge_class }}-lt">{{ strtoupper($task->priority) }}</span>
        @if($task->assignee)
        <span class="avatar avatar-xs rounded-circle" title="{{ $task->assignee->name }}">
            @if($task->assignee->avatar_url)
                <img src="{{ $task->assignee->avatar_url }}" alt="">
            @else
                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
            @endif
        </span>
        @endif
    </div>
</div>
