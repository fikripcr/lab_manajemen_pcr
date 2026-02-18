<div class="btn-group btn-group-sm">
    <x-tabler.button href="{{ route('eoffice.jenis-layanan.show', $row->hashid) }}" class="btn-icon btn-ghost-info" icon="ti ti-settings" title="Manage Detail/PIC/Isian" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn" 
        data-url="{{ route('eoffice.jenis-layanan.edit', $row->hashid) }}" 
        data-modal-title="Edit Jenis Layanan" 
        title="Edit"
        icon="ti ti-pencil" />
    <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" 
        data-url="{{ route('eoffice.jenis-layanan.destroy', $row->hashid) }}" 
        data-title="Hapus?" 
        data-text="Menghapus jenis layanan ini akan berdampak pada data terkait lainnya." 
        icon="ti ti-trash" />
</div>
