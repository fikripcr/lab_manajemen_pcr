@props([
    'approvals' => [],
    'emptyText' => 'Belum ada riwayat approval.',
    'canModify' => true,
])

@php
    $approvalsCollection = collect($approvals ?? []);
@endphp

@if($approvalsCollection->isEmpty())
    <div class="text-center text-muted py-4">
        <i class="ti ti-info-circle mb-2 h2 d-block"></i>
        {{ $emptyText }}
    </div>
@else
    <ul class="timeline">
        @foreach($approvalsCollection as $approval)
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
                    <x-tabler.card-body class="p-2">
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

                        @if($canModify)
                            <div class="mt-3">
                                <x-tabler.button
                                    class="btn-sm btn-animate-icon btn-outline ajax-modal-btn"
                                    text=" Set Persetujuan"
                                    icon="ti ti-edit"
                                    :data-url="route('hr.approval.show', $approval->encrypted_sys_approval_id)"
                                    data-modal-title="Eksekusi Persetujuan"
                                />
                            </div>
                        @endif
                    </x-tabler.card-body>
                </div>
            </li>
        @endforeach
    </ul>
@endif
