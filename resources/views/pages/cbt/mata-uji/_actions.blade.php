<div class="btn-list flex-nowrap">
    <a href="{{ route('cbt.mata-uji.show', $mu->encrypted_mata_uji_id) }}" class="btn btn-icon btn-sm btn-primary" title="Detail">
        <i class="ti ti-eye"></i>
    </a>
    <button type="button" class="btn btn-icon btn-sm btn-info ajax-modal-btn" 
        data-url="{{ route('cbt.mata-uji.edit', $mu->encrypted_mata_uji_id) }}" 
        data-modal-title="Edit Mata Uji"
        title="Edit">
        <i class="ti ti-pencil"></i>
    </button>
    <button type="button" class="btn btn-icon btn-sm btn-danger ajax-delete" 
        data-url="{{ route('cbt.mata-uji.destroy', $mu->encrypted_mata_uji_id) }}"
        title="Hapus">
        <i class="ti ti-trash"></i>
    </button>
</div>
