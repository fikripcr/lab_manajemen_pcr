<div class="d-flex justify-content-center gap-1">
    @if($row->subject)
        <a href="{{ route('pemutu.dokumen.index', ['jenis' => strtolower($row->subject->jenis), 'id' => $row->subject->encrypted_dok_id, 'type' => strtolower($row->subject->jenis)]) }}"
           class="btn btn-sm {{ $row->status === 'Pending' ? 'btn-primary' : 'btn-outline-secondary' }}">
            <i class="ti ti-{{ $row->status === 'Pending' ? 'external-link' : 'eye' }} me-1"></i>
            {{ $row->status === 'Pending' ? 'Eksekusi' : 'Lihat' }}
        </a>
    @endif
</div>
