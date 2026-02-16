<div class="btn-group">
    <a href="{{ route('pmb.pendaftaran.show', $pendaftaran->encrypted_id) }}" class="btn btn-sm btn-info" title="Detail">
        <i class="ti ti-eye"></i>
    </a>
    @if(in_array($pendaftaran->status_terkini, ['Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas']))
    <button type="button" class="btn btn-sm btn-success btn-verify" data-id="{{ $pendaftaran->encrypted_id }}" title="Verifikasi">
        <i class="ti ti-check"></i>
    </button>
    @endif
</div>
