<div class="btn-group">
    <a href="{{ route('cbt.paket.show', $p->hashid) }}" class="btn btn-sm btn-primary">
        <i class="ti ti-eye"></i> Detail Soal
    </a>
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" 
        data-url="{{ route('cbt.paket.edit', $p->hashid) }}"
        data-modal-title="Edit Paket">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" 
        data-url="{{ route('cbt.paket.destroy', $p->hashid) }}">
        <i class="ti ti-trash"></i>
    </button>
</div>
