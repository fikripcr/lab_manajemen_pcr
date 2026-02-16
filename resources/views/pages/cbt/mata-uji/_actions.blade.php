<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-action" data-title="Edit Mata Uji" data-url="{{ route('cbt.mata-uji.edit', $mu->encrypted_id) }}">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('cbt.mata-uji.destroy', $mu->encrypted_id) }}" data-table="#table-mata-uji">
        <i class="ti ti-trash"></i>
    </button>
</div>
