<div class="btn-list flex-nowrap justify-content-end">
    <x-tabler.button href="{{ route('pemutu.indikators.show', $row->indikator_id) }}" class="btn-sm btn-icon btn-ghost-info" icon="ti ti-eye" title="Detail" />
    <x-tabler.button href="{{ route('pemutu.indikators.edit', $row->indikator_id) }}" class="btn-sm btn-icon btn-ghost-primary" icon="ti ti-pencil" title="Edit" />
    <x-tabler.button type="button" class="btn-sm btn-icon btn-ghost-danger ajax-delete" 
        data-url="{{ route('pemutu.indikators.destroy', $row->indikator_id) }}" 
        data-title="Hapus Indikator?" 
        data-text="Data ini akan dihapus permanen." 
        icon="ti ti-trash" />
</div>
