<x-tabler.button-group class="btn-list flex-nowrap">
    <x-tabler.button href="{{ route('pemutu.dokumens.show', $row) }}" class="btn-icon btn-ghost-info" icon="ti ti-eye" title="Detail" iconOnly="true" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn" 
        data-url="{{ route('pemutu.dokumens.edit', $row) }}" 
        data-modal-title="Edit Dokumen" 
        title="Edit"
        icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" 
        data-url="{{ route('pemutu.dokumens.destroy', $row) }}" 
        data-title="Hapus?" 
        data-text="Dokumen ini akan dihapus permanen." 
        icon="ti ti-trash" />
</x-tabler.button-group>
