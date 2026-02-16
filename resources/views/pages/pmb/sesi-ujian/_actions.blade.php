<div class="btn-list flex-nowrap">
    <button type="button" 
            class="btn btn-icon btn-sm btn-primary ajax-modal-btn"
            data-modal-target="#modalAction"
            data-modal-title="Edit Sesi Ujian"
            data-url="{{ route('pmb.sesi-ujian.edit', $model->encrypted_id) }}"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Edit">
        <i class="ti ti-pencil"></i>
    </button>
    <button type="button" 
            class="btn btn-icon btn-sm btn-danger delete-btn"
            data-url="{{ route('pmb.sesi-ujian.destroy', $model->encrypted_id) }}"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Hapus">
        <i class="ti ti-trash"></i>
    </button>
</div>
