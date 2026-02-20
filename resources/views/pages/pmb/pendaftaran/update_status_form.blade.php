<x-tabler.form-modal
    title="Ubah Status Pendaftaran: {{ $pendaftaran->no_pendaftaran }}"
    route="{{ route('pmb.pendaftaran.update-status', $pendaftaran->encrypted_pendaftaran_id) }}"
    method="POST"
    submitText="Update Status"
    data-redirect="true"
>
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
</x-tabler.form-modal>
