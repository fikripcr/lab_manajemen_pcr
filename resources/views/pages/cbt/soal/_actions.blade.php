<div class="btn-group">
    <a href="{{ route('cbt.soal.edit', $s->encrypted_soal_id) }}" class="btn btn-sm btn-info">
        <i class="ti ti-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" 
        data-url="{{ route('cbt.soal.destroy', $s->encrypted_soal_id) }}">
        <i class="ti ti-trash"></i>
    </button>
</div>
