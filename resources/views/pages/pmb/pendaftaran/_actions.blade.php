<div class="btn-group">
    <x-tabler.button href="{{ route('pmb.pendaftaran.show', $pendaftaran->encrypted_id) }}" class="btn-sm btn-info" title="Detail" icon="ti ti-eye" />
    @if(in_array($pendaftaran->status_terkini, ['Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas']))
    <x-tabler.button type="button" class="btn-sm btn-success btn-verify" data-id="{{ $pendaftaran->encrypted_id }}" title="Verifikasi" icon="ti ti-check" />
    @endif
</div>
