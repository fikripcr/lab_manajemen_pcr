<div class="btn-group btn-group-sm">
    <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn" 
        data-url="{{ route('eoffice.kategori-perusahaan.edit', $row->encrypted_kategoriperusahaan_id) }}" 
        data-modal-title="Edit Kategori" 
        title="Edit"
        icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" 
        data-url="{{ route('eoffice.kategori-perusahaan.destroy', $row->encrypted_kategoriperusahaan_id) }}" 
        data-title="Hapus?" 
        data-text="Kategori ini akan dihapus permanen." 
        icon="ti ti-trash" />
</div>
