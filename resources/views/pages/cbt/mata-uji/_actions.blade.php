<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" 
        data-url="{{ route('cbt.mata-uji.edit', $mu->hashid) }}" 
        data-modal-title="Edit Mata Uji">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" 
        data-url="{{ route('cbt.mata-uji.destroy', $mu->hashid) }}">
        <i class="ti ti-trash"></i>
    </button>
</div>
