<div class="kanban-task-card" data-task-id="{{ $task->encrypted_project_task_id }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="badge badge-sm {{ $task->priority_badge_class }}">
            {{ ucfirst($task->priority) }}
        </span>
        @if($task->weight > 1)
        <span class="badge badge-sm bg-secondary-lt">
            <i class="ti ti-weight me-1"></i>{{ $task->weight }}
        </span>
        @endif
    </div>
    
    <h6 class="mb-2 fs-6">{{ $task->task_title }}</h6>
    
    @if($task->task_desc)
    <p class="text-muted small mb-2 text-truncate-2">
        {{ Str::limit($task->task_desc, 80) }}
    </p>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="d-flex align-items-center">
            @if($task->assignee)
            <span class="avatar avatar-xs me-2" title="{{ $task->assignee->name }}">
                {{ substr($task->assignee->name, 0, 1) }}
            </span>
            <span class="text-muted small">{{ Str::limit($task->assignee->name, 15) }}</span>
            @else
            <span class="text-muted small">
                <i class="ti ti-user me-1"></i>Unassigned
            </span>
            @endif
        </div>
        
        @if($task->due_date)
        <span class="text-muted small" title="{{ formatTanggalIndo($task->due_date) }}">
            <i class="ti ti-calendar {{ $task->due_date->isPast() ? 'text-danger' : '' }}"></i>
        </span>
        @endif
    </div>
</div>
