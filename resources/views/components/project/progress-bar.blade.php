@props(['percentage' => 0])

<div class="d-flex align-items-center">
    <div class="flex-grow-1">
        <div class="progress progress-sm" style="height: 6px;">
            <div class="progress-bar {{ $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-primary' : 'bg-warning') }}" 
                 style="width: {{ $percentage }}%" 
                 aria-valuenow="{{ $percentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
        </div>
    </div>
    <div class="ms-2 small text-muted">{{ number_format($percentage, 0) }}%</div>
</div>
