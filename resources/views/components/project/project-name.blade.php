<div class="d-flex align-items-center">
    <div class="me-2">
        @if($project->is_agile)
            <i class="ti ti-sprint text-azure"></i>
        @else
            <i class="ti ti-briefcase text-primary"></i>
        @endif
    </div>
    <div>
        <div class="fw-bold">{{ $project->project_name }}</div>
        @if($project->project_desc)
        <div class="small text-muted text-truncate" style="max-width: 200px;">
            {{ Str::limit($project->project_desc, 50) }}
        </div>
        @endif
    </div>
</div>
