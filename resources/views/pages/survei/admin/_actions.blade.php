<div class="btn-group">
    <a href="{{ route('survei.builder', $s->id) }}" class="btn btn-sm btn-primary" title="Form Builder">
        <i class="ti ti-tool"></i>
    </a>
    <a href="{{ route('survei.responses', $s->id) }}" class="btn btn-sm btn-cyan" title="Lihat Jawaban">
        <i class="ti ti-chart-bar"></i>
    </a>
    @if($s->is_aktif && $s->slug)
    <button type="button" class="btn btn-sm btn-indigo btn-copy-link"
            data-link="{{ route('survei.public.show', $s->slug) }}"
            title="Salin Link Survei">
        <i class="ti ti-link"></i>
    </button>
    @endif
    <button type="button"
            class="btn btn-sm {{ $s->is_aktif ? 'btn-success' : 'btn-warning' }} btn-toggle-status"
            data-url="{{ route('survei.toggle-status', $s->id) }}"
            data-title="{{ $s->is_aktif ? 'Unpublish survei ini?' : 'Publish survei ini?' }}"
            title="{{ $s->is_aktif ? 'Unpublish' : 'Publish' }}">
        <i class="ti {{ $s->is_aktif ? 'ti-eye' : 'ti-eye-off' }}"></i>
    </button>
    <button type="button" class="btn btn-sm btn-info ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Edit Survei" data-url="{{ route('survei.edit', $s->id) }}">
        <i class="ti ti-settings"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('survei.destroy', $s->id) }}" data-title="Hapus Survei?">
        <i class="ti ti-trash"></i>
    </button>
</div>
