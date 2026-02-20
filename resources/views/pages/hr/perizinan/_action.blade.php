<div class="btn-group">
    <x-tabler.button type="button" class="btn-sm btn-icon btn-info ajax-modal-btn"
        data-url="{{ route('hr.perizinan.show', $row->encrypted_perizinan_id) }}"
        data-modal-title="Detail Perizinan"
        icon="ti ti-eye" />

    @if ($row->status === 'Draft')
        <x-tabler.button type="button" class="btn-sm btn-icon btn-primary ajax-modal-btn"
            data-url="{{ route('hr.perizinan.edit', $row->encrypted_perizinan_id) }}"
            data-modal-title="Edit Perizinan"
            icon="ti ti-pencil" />
            
        <x-tabler.button type="button" class="btn-sm btn-icon btn-danger btn-delete"
            data-url="{{ route('hr.perizinan.destroy', $row->encrypted_perizinan_id) }}"
            icon="ti ti-trash" />
    @endif
</div>
