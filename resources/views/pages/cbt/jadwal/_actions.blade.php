<div class="btn-group">
    <button type="button" class="btn btn-sm btn-warning btn-jadwal-action" data-url="{{ route('cbt.jadwal.generate-token', $j->hashid) }}" title="Generate Token Baru">
        <i class="ti ti-rotate"></i>
    </button>
    <button type="button" class="btn btn-sm btn-{{ $j->is_token_aktif ? 'success' : 'secondary' }} btn-jadwal-action" data-url="{{ route('cbt.jadwal.toggle-token', $j->hashid) }}" title="{{ $j->is_token_aktif ? 'Nonaktifkan Token' : 'Aktifkan Token' }}">
        <i class="ti ti-{{ $j->is_token_aktif ? 'eye' : 'eye-off' }}"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger ajax-delete" data-url="{{ route('cbt.jadwal.destroy', $j->hashid) }}">
        <i class="ti ti-trash"></i>
    </button>
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('cbt.execute.start', $j->hashid) }}" class="btn btn-sm btn-primary" title="Test Ujian (Admin Bypass)">
        <i class="ti ti-play-card"></i>
    </a>
    @endif
</div>
