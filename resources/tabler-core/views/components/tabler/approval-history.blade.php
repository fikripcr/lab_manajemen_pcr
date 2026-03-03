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

        @if($approvals->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="ti ti-info-circle mb-2 h2 d-block"></i>
                {{ $emptyText }}
            </div>
        @else
            <ul class="timeline">
                @foreach($approvals as $approval)
                    @php
                        $status = $approval->status;
                        $point  = match ($status) {
                            'approved', 'Approved' => 'success',
                            'rejected', 'Rejected' => 'danger',
                            'tangguhkan', 'Tangguhkan' => 'warning',
                            'pending', 'Pending' => 'secondary',
                            default => 'secondary',
                        };
                        $icon = getApprovalIcon($approval->status);
                    @endphp

                    <li class="timeline-event">
                        <div class="timeline-event-icon bg-{{ $point }}-lt">
                            <i class="{{ $icon }}"></i>
                        </div>

                        <div class="timeline-event-card shadow-none border">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold">{{ $approval->pejabat ?? '-' }} {!! getApprovalBadge($approval->status) !!}</div>
                                        @if($approval->jabatan)
                                            <div class="text-muted small">{{ $approval->jabatan }}</div>
                                        @endif
                                    </div>
                                    <div class="text-muted small text-end">{{ formatTanggalWaktuIndo($approval->created_at) }}</div>
                                </div>

                                @if($approval->catatan)
                                    <div class="text-muted mt-1 small">{{ $approval->catatan }}</div>
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
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</div>
