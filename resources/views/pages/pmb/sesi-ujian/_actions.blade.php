<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Edit Sesi" data-url="{{ route('pmb.sesi-ujian.edit', $s->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('pmb.sesi-ujian.destroy', $s->encrypted_id) }}" data-title="Hapus Sesi?">
        <i class="ti ti-trash"></i>
    </button>
</div>
