<div class="btn-group">
    <x-tabler.button type="button" class="btn-sm btn-info ajax-modal-btn"
        data-modal-target="#modalAction" data-modal-title="Edit Periode"
        data-url="{{ route('pmb.periode.edit', $p->encrypted_periode_id) }}" icon="ti ti-edit" />
        
    <x-tabler.button type="button" class="btn-sm btn-danger ajax-delete"
        data-url="{{ route('pmb.periode.destroy', $p->encrypted_periode_id) }}"
        data-title="Hapus Periode?" icon="ti ti-trash" />
</div>
