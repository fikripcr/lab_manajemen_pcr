<div class="btn-group">
    <a href="{{ route('cbt.soal.edit', $s->encrypted_id) }}" class="btn btn-sm btn-info">
        <i class="ti ti-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('cbt.soal.destroy', $s->encrypted_id) }}" data-table="#table-soal">
        <i class="ti ti-trash"></i>
    </button>
</div>
