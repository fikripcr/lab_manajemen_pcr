<x-tabler.form-modal
    title="Ajukan Perubahan Jabatan Fungsional"
    route="{{ route('hr.pegawai.jabatan-fungsional.store', $pegawai->encrypted_pegawai_id) }}"
    method="POST"
    submitText="Ajukan Perubahan"
>
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex">
            <div>
                <i class="ti ti-info-circle fs-2 me-2"></i>
            </div>
            <div>
                <h4 class="alert-title">Informasi Jabatan</h4>
                <div class="text-muted">
                    Jabatan Fungsional saat ini: <strong>{{ $pegawai->latestJabatanFungsional->jabatanFungsional->jabfungsional ?? 'Belum ada' }}</strong><br>
                    Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="jabfungsional_id" label="Jabatan Fungsional Baru" required="true">
                <option value="">Pilih Jabatan</option>
                @foreach($jabatan as $item)
                    <option value="{{ $item->jabfungsional_id }}">{{ $item->jabfungsional }} ({{ $item->kode }})</option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tmt" label="TMT (Terhitung Mulai Tanggal)" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="no_sk_internal" label="Nomor SK Internal" />
        </div>
    </div>
</x-tabler.form-modal>
