<div class="btn-group">
    <a href="{{ route('cbt.paket.show', $p->encrypted_id) }}" class="btn btn-sm btn-primary">
        <i class="ti ti-eye"></i> Detail Soal
    </a>
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-action" data-title="Edit Paket" data-url="{{ route('cbt.paket.edit', $p->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('cbt.paket.destroy', $p->encrypted_id) }}" data-table="#table-paket">
        <i class="ti ti-trash"></i>
    </button>
</div>
