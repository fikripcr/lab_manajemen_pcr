<div class="d-flex gap-1 justify-content-center">
    <x-tabler.button type="button" class="btn-outline-primary btn-sm ajax-modal-btn" icon="ti ti-eye" text="Detail"
        data-modal-target="#modalAction" data-modal-title="Detail Pengajuan"
        data-modal-size="modal-xl"
        data-url="{{ route('hr.approval.show', $row->riwayatapproval_id) }}" />
    @if($row->status === 'Pending')
        <x-tabler.button type="button" class="btn-success btn-sm btn-process" icon="ti ti-check" text="Setujui"
            data-url="{{ route('hr.approval.process', $row->riwayatapproval_id) }}"
            data-status="Approved" data-need-reason="0" />
        <x-tabler.button type="button" class="btn-warning btn-sm btn-process" icon="ti ti-clock-pause" text="Tangguhkan"
            data-url="{{ route('hr.approval.process', $row->riwayatapproval_id) }}"
            data-status="Tangguhkan" data-need-reason="0" />
        <x-tabler.button type="button" class="btn-danger btn-sm btn-process" icon="ti ti-x" text="Tolak"
            data-url="{{ route('hr.approval.process', $row->riwayatapproval_id) }}"
            data-status="Rejected" data-need-reason="1" />
    @endif
</div>
