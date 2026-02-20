<div class="btn-list flex-nowrap">
    <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-primary ajax-modal-btn"
        data-modal-target="#modalAction"
        data-modal-title="Edit Sesi Ujian"
        data-url="{{ route('pmb.sesi-ujian.edit', $s->encrypted_sesiujian_id) }}"
        title="Edit">
        <i class="ti ti-pencil"></i>
    </a>
    <button type="button" class="btn btn-icon btn-sm btn-danger delete-btn"
        data-url="{{ route('pmb.sesi-ujian.destroy', $s->encrypted_sesiujian_id) }}"
        title="Hapus">
        <i class="ti ti-trash"></i>
    </button>
    <a href="{{ route('cbt.dashboard') }}" class="btn btn-icon btn-sm btn-success" 
        title="Test Ujian">
        <i class="ti ti-player-play"></i>
    </a>
</div>
