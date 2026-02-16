<form action="{{ route('pmb.pendaftaran.update-status', $pendaftaran->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Ubah Status Pendaftaran: {{ $pendaftaran->no_pendaftaran }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Status Baru</label>
            <select name="status" class="form-select">
                <option value="Menunggu_Verifikasi_Berkas" {{ $pendaftaran->status_terkini == 'Menunggu_Verifikasi_Berkas' ? 'selected' : '' }}>Menunggu Verifikasi Berkas</option>
                <option value="Siap_Ujian" {{ $pendaftaran->status_terkini == 'Siap_Ujian' ? 'selected' : '' }}>Siap Ujian / Berkas Oke</option>
                <option value="Lulus" {{ $pendaftaran->status_terkini == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="Tidak_Lulus" {{ $pendaftaran->status_terkini == 'Tidak_Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
            </select>
        </div>
        <x-tabler.form-textarea name="keterangan" label="Keterangan / Catatan" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Update Status</button>
    </div>
</form>
