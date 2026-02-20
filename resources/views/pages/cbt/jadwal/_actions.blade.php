<div class="btn-list flex-nowrap">
    <button type="button" class="btn btn-icon btn-sm btn-warning btn-jadwal-action" data-url="{{ route('cbt.jadwal.generate-token', $j) }}" title="Generate Token Baru">
        <i class="ti ti-rotate"></i>
    </button>
    <button type="button" class="btn btn-icon btn-sm btn-{{ $j->is_token_aktif ? 'success' : 'secondary' }} btn-jadwal-action" data-url="{{ route('cbt.jadwal.toggle-token', $j) }}" title="{{ $j->is_token_aktif ? 'Nonaktifkan Token' : 'Aktifkan Token' }}">
        <i class="ti ti-{{ $j->is_token_aktif ? 'eye' : 'eye-off' }}"></i>
    </button>
    <button type="button" class="btn btn-icon btn-sm btn-danger ajax-delete" data-url="{{ route('cbt.jadwal.destroy', $j) }}" title="Hapus">
        <i class="ti ti-trash"></i>
    </button>
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('cbt.execute.start', $j) }}" class="btn btn-icon btn-sm btn-primary" title="Test Ujian (Admin Bypass)">
        <i class="ti ti-play-card"></i>
    </a>
    @endif
</div>
