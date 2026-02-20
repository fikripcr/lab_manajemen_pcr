<x-tabler.form-modal
    title="Verifikasi Dokumen: {{ $document->jenisDokumen->nama_dokumen }}"
    route="{{ route('pmb.pendaftaran.verify-document', $document->encrypted_dokumenupload_id) }}"
    method="POST"
    submitText="Simpan Verifikasi"
    data-redirect="true"
>
    <div class="mb-3">
        <label class="form-label">Status Verifikasi</label>
        <select name="status" class="form-select" required>
            <option value="Valid" {{ $document->status_verifikasi == 'Valid' ? 'selected' : '' }}>Valid / Benar</option>
            <option value="Revisi" {{ $document->status_verifikasi == 'Revisi' ? 'selected' : '' }}>Butuh Revisi</option>
            <option value="Ditolak" {{ $document->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <x-tabler.form-textarea name="keterangan" label="Catatan / Keterangan" placeholder="Berikan catatan jika butuh revisi atau ditolak..." />
</x-tabler.form-modal>
