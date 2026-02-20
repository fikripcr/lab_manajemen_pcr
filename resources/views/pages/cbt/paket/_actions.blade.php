<div class="btn-group">
    <a href="{{ route('cbt.paket.show', $p->encrypted_paket_ujian_id) }}" class="btn btn-sm btn-primary">
        <i class="ti ti-eye"></i> Detail Soal
    </a>
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" 
        data-url="{{ route('cbt.paket.edit', $p->encrypted_paket_ujian_id) }}"
        data-modal-title="Edit Paket">
        <i class="ti ti-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" 
        data-url="{{ route('cbt.paket.destroy', $p->encrypted_paket_ujian_id) }}">
        <i class="ti ti-trash"></i>
    </button>
</div>
