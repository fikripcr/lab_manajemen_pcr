<div class="btn-group btn-group-sm" role="group">
    <x-tabler.button type="button" class="btn-icon btn-info ajax-modal-btn"
        data-url="{{ route('hr.lembur.show', $row->encrypted_lembur_id) }}"
        data-modal-title="Detail Lembur"
        icon="ti ti-eye" />
    <x-tabler.button type="button" class="btn-icon btn-warning ajax-modal-btn"
        data-url="{{ route('hr.lembur.edit', $row->encrypted_lembur_id) }}"
        data-modal-title="Edit Lembur"
        icon="ti ti-edit" />
    <x-tabler.button type="button" class="btn-icon btn-danger ajax-delete-btn"
        data-url="{{ route('hr.lembur.destroy', $row->encrypted_lembur_id) }}"
        icon="ti ti-trash" />
</div>
