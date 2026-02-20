<div class="btn-group btn-group-sm">
    <x-tabler.button href="{{ route('eoffice.perusahaan.show', $row->encrypted_perusahaan_id) }}" class="btn-icon btn-ghost-info" icon="ti ti-eye" title="Detail" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn" 
        data-url="{{ route('eoffice.perusahaan.edit', $row->encrypted_perusahaan_id) }}" 
        data-modal-title="Edit Perusahaan" 
        title="Edit"
        icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" 
        data-url="{{ route('eoffice.perusahaan.destroy', $row->encrypted_perusahaan_id) }}" 
        data-title="Hapus?" 
        data-text="Perusahaan ini akan dihapus permanen." 
        icon="ti ti-trash" />
</div>
