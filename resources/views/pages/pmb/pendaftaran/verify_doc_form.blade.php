<form action="{{ route('pmb.pendaftaran.verify-document', $document->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Verifikasi Dokumen: {{ $document->jenisDokumen->nama_dokumen }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Status Verifikasi</label>
            <select name="status" class="form-select" required>
                <option value="Valid" {{ $document->status_verifikasi == 'Valid' ? 'selected' : '' }}>Valid / Benar</option>
                <option value="Revisi" {{ $document->status_verifikasi == 'Revisi' ? 'selected' : '' }}>Butuh Revisi</option>
                <option value="Ditolak" {{ $document->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <x-tabler.form-textarea name="keterangan" label="Catatan / Keterangan" placeholder="Berikan catatan jika butuh revisi atau ditolak..." />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Verifikasi</button>
    </div>
</form>
