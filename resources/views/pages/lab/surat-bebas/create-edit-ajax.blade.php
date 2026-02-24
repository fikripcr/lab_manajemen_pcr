<x-tabler.form-modal
    id_form="{{ $surat->exists ? 'editSuratBebasForm' : 'createSuratBebasForm' }}"
    title="{{ $surat->exists ? 'Update Status Surat Bebas Lab' : 'Ajukan Surat Bebas Lab' }}"
    route="{{ $surat->exists ? route('lab.surat-bebas.status', $surat->encrypted_surat_bebas_lab_id) : route('lab.surat-bebas.store') }}"
    method="{{ $surat->exists ? 'POST' : 'POST' }}"
>
    @if($surat->exists)
        @method('POST') {{-- Controller uses updateStatus via POST route lab.surat-bebas.status --}}
    @endif

    @if(!$surat->exists)
        <div class="alert alert-info">
            Pastikan anda <strong>TIDAK</strong> memiliki tanggungan peminjaman alat atau masalah administrasi lab lainnya sebelum mengajukan surat ini.
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <div class="form-control-plaintext font-weight-bold">{{ auth()->user()->name }}</div>
        </div>

        <x-tabler.form-textarea
            name="catatan"
            label="Catatan Tambahan (Opsional)"
            rows="3"
            placeholder="Keterangan tambahan jika diperlukan..."
        />
    @else
        <div class="mb-3">
            <label class="form-label">Status Kelulusan Lab</label>
            <x-tabler.form-select
                name="status"
                :options="[
                    'approved' => 'Approved (Bebas Lab)',
                    'rejected' => 'Rejected (Masih Ada Tanggungan)'
                ]"
                selected="{{ $surat->status }}"
                required
            />
        </div>

        <x-tabler.form-textarea
            name="catatan"
            label="Catatan / Alasan"
            rows="3"
            placeholder="Masukkan alasan jika ditolak atau catatan tambahan..."
        >{{ old('catatan', $surat->latestApproval?->catatan) }}</x-tabler.form-textarea>
    @endif
</x-tabler.form-modal>
