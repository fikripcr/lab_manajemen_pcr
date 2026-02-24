<div class="d-flex align-items-center">
    <div class="me-2">
        <i class="ti ti-list-check text-primary"></i>
    </div>
    <div>
        <div class="fw-bold">{{ $task->task_title }}</div>
        @if($task->assignee)
        <div class="small text-muted">
            <i class="ti ti-user me-1"></i>{{ $task->assignee->name }}
        </div>
        @endif
    </div>
</div>
