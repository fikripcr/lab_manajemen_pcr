<div class="btn-group btn-group-sm">
    <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn" data-url="{{ route('eoffice.kategori-isian.edit', $row->encrypted_kategoriisian_id) }}" data-modal-title="Edit Isian" title="Edit" icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" data-url="{{ route('eoffice.kategori-isian.destroy', $row->encrypted_kategoriisian_id) }}" data-title="Hapus?" data-text="Isian ini akan dihapus permanen." icon="ti ti-trash" />
</div>
