<div class="btn-group">
    <x-tabler.button type="button" class="btn-sm btn-info ajax-modal-btn"
        data-modal-target="#modalAction" data-modal-title="Edit Jenis Dokumen"
        data-url="{{ route('pmb.jenis-dokumen.edit', $d->encrypted_id) }}" icon="ti ti-edit" />
        
    <x-tabler.button type="button" class="btn-sm btn-danger ajax-delete"
        data-url="{{ route('pmb.jenis-dokumen.destroy', $d->encrypted_id) }}"
        data-title="Hapus Jenis Dokumen?" icon="ti ti-trash" />
</div>
