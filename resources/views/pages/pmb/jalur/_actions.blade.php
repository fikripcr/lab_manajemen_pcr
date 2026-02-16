<div class="btn-group">
    <a href="{{ route('pmb.syarat-jalur.index', ['jalur' => $j->encrypted_id]) }}" class="btn btn-sm btn-warning" title="Syarat Dokumen">
        <i class="ti ti-file-certificate"></i>
    </a>
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Edit Jalur" data-url="{{ route('pmb.jalur.edit', $j->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('pmb.jalur.destroy', $j->encrypted_id) }}" data-title="Hapus Jalur?">
        <i class="ti ti-trash"></i>
    </button>
</div>
