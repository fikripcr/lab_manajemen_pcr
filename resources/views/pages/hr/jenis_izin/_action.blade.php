<div class="btn-group btn-group-sm">
    <x-tabler.button type="button" class="btn-icon btn-primary ajax-modal-btn"
        data-url="{{ route('hr.jenis-izin.edit', $row->encrypted_jenisizin_id) }}"
        data-modal-title="Edit Jenis Izin"
        icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-danger btn-delete"
        data-url="{{ route('hr.jenis-izin.destroy', $row->encrypted_jenisizin_id) }}"
        icon="ti ti-trash" />
</div>
