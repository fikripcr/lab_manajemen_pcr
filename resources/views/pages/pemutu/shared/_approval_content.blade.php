<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="card-title mb-0">Riwayat Approval & Legalitas</h4>
    <x-tabler.button 
        type="button" 
        class="btn-primary btn-sm ajax-modal-btn" 
        data-url="{{ route('pemutu.dokumens.approve.create', $dokumen) }}" 
        data-modal-title="Submit Approval & Legalitas" 
        icon="ti ti-checkup-list" 
        text="Submit Approval" 
    />
</div>

@if($dokumen->approvals->count() > 0)
    <div class="divide-y">
        @foreach($dokumen->approvals()->latest()->get() as $approval)
            <div class="py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-sm rounded">{{ substr($approval->approver->nama ?? '?', 0, 1) }}</span>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-bold">{{ $approval->approver->nama ?? 'Unknown' }}</div>
                            <div class="text-muted small">{{ $approval->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-muted small">{{ $approval->proses }} @if($approval->jabatan) ({{ $approval->jabatan }}) @endif</div>
                        
                        @foreach($approval->statuses as $status)
                            <div class="mt-2 p-3 rounded bg-body-tertiary border position-relative">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge bg-{{ $status->status_approval === 'terima' ? 'success' : ($status->status_approval === 'tolak' ? 'danger' : 'warning') }}-lt">
                                        {{ strtoupper($status->status_approval) }}
                                    </span>
                                </div>
                                @if($status->komentar)
                                    <div class="text-muted small mt-1">
                                        <i class="ti ti-message-2 me-1"></i> {{ $status->komentar }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="col-auto">
                        @if($approval->approver && $approval->approver->user_id === auth()->id())
                            <x-tabler.button 
                                type="button" 
                                class="btn-icon btn-sm btn-ghost-danger ajax-delete" 
                                data-url="{{ route('pemutu.dokumens.approval.destroy', $approval->encrypted_dokapproval_id) }}" 
                                data-title="Hapus Approval?" 
                                data-text="Data approval ini akan dihapus permanen."
                                icon="ti ti-trash" 
                            />
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty py-5">
        <div class="empty-icon"><i class="ti ti-ghost" style="font-size: 3rem;"></i></div>
        <p class="empty-title">Belum ada riwayat approval</p>
        <p class="empty-subtitle text-muted">Silahkan ajukan approval untuk melegalkan dokumen ini.</p>
    </div>
@endif
