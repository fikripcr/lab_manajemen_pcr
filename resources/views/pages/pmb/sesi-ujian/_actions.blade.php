<div class="btn-list flex-nowrap">
    <x-tabler.button type="button" class="btn-icon btn-sm btn-primary ajax-modal-btn"
        data-modal-target="#modalAction"
        data-modal-title="Edit Sesi Ujian"
        data-url="{{ route('pmb.sesi-ujian.edit', $model->encrypted_id) }}"
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="Edit" icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-sm btn-danger delete-btn"
        data-url="{{ route('pmb.sesi-ujian.destroy', $model->encrypted_id) }}"
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="Hapus" icon="ti ti-trash" />
</div>
