<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Edit Jenis Dokumen" data-url="{{ route('pmb.jenis-dokumen.edit', $d->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('pmb.jenis-dokumen.destroy', $d->encrypted_id) }}" data-title="Hapus Jenis Dokumen?">
        <i class="ti ti-trash"></i>
    </button>
</div>
