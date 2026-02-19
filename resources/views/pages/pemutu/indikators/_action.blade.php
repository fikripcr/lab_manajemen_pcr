    <x-tabler.button href="{{ route('pemutu.indikators.show', $row->indikator_id) }}" iconOnly class="btn-ghost-info" icon="ti ti-eye" />
    <x-tabler.button href="{{ route('pemutu.indikators.edit', $row->indikator_id) }}" iconOnly class="btn-ghost-primary" icon="ti ti-pencil" />
    <x-tabler.button type="button" iconOnly class="btn-ghost-danger ajax-delete" 
        data-url="{{ route('pemutu.indikators.destroy', $row->indikator_id) }}" 
        data-title="Hapus Indikator?" 
        data-text="Data ini akan dihapus permanen." 
        icon="ti ti-trash" />
