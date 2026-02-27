@php
    $properties = $activity->properties;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-label text-muted small uppercase">Log Name</div>
        <div class="fw-bold">{{ $activity->log_name }}</div>
    </div>
    <div class="col-md-6">
        <div class="form-label text-muted small uppercase">Time</div>
        <div class="fw-bold">{{ formatTanggalWaktuIndo($activity->created_at) }}</div>
    </div>
    <div class="col-md-6">
        <div class="form-label text-muted small uppercase">Event</div>
        <div class="fw-bold">
            <span class="badge bg-blue-lt">{{ $activity->event ?? 'N/A' }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-label text-muted small uppercase">User</div>
        <div class="fw-bold">{{ $activity->causer ? $activity->causer->name : 'System' }}</div>
    </div>
    <div class="col-12">
        <div class="form-label text-muted small uppercase">Description</div>
        <div class="text-wrap">{{ $activity->description }}</div>
    </div>

    @if($activity->subject)
    <div class="col-12">
        <div class="form-label text-muted small uppercase">Subject</div>
        <div class="bg-light p-2 rounded">
            <code>{{ $activity->subject_type }}: #{{ $activity->subject_id }}</code>
        </div>
    </div>
    @endif

    @if($properties && count($properties) > 0)
    <div class="col-12">
        <div class="form-label text-muted small uppercase mt-2">Properties / Changes</div>
        <div class="bg-dark text-white p-3 rounded">
            <pre class="mb-0 text-success" style="white-space: pre-wrap;">{{ json_encode($properties, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
    @endif
</div>
