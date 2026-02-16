<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Edit Periode" data-url="{{ route('pmb.periode.edit', $p->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('pmb.periode.destroy', $p->encrypted_id) }}" data-title="Hapus Periode?">
        <i class="ti ti-trash"></i>
    </button>
</div>
