@props([
    'approvals' => [],
    'title'     => 'Riwayat Approval',
    'emptyText' => 'Belum ada riwayat approval.',
])

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
    </div>
    <div class="card-body">
        @forelse($approvals as $approval)
            @php
                $status = $approval->status;
                $point  = match ($status) {
                    'approved', 'Approved' => 'success',
                    'rejected', 'Rejected' => 'danger',
                    'tangguhkan', 'Tangguhkan' => 'warning',
                    'pending', 'Pending' => 'secondary',
                    default => 'secondary',
                };
            @endphp

            @if($loop->first)
                <div class="timeline">
            @endif

            <div class="timeline-item">
                <div class="timeline-point timeline-point-{{ $point }}">
                    <i class="{{ getApprovalIcon($approval->status) }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-time">{{ formatTanggalWaktuIndo($approval->created_at) }}</div>
                    <div class="timeline-title">
                        <span class="me-2">{{ $approval->pejabat ?? '-' }}</span>
                        {!! getApprovalBadge($approval->status) !!}
                    </div>

                    @if($approval->jabatan)
                        <div class="text-muted small">{{ $approval->jabatan }}</div>
                    @endif

                    @if($approval->catatan)
                        <div class="text-muted mt-1">{{ $approval->catatan }}</div>
                    @endif

                    @if($approval->lampiran_url)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $approval->lampiran_url) }}" target="_blank" class="btn btn-sm btn-ghost-info">
                                <i class="ti ti-paperclip"></i> Lihat lampiran
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($loop->last)
                </div>
            @endif
        @empty
            <div class="text-center text-muted py-4">
                <i class="ti ti-info-circle mb-2 h2 d-block"></i>
                {{ $emptyText }}
            </div>
        @endforelse
    </div>
</div>
