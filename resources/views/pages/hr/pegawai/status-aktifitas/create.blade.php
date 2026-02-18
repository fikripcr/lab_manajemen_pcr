<x-tabler.form-modal
    title="Ajukan Perubahan Status Aktifitas"
    route="{{ route('hr.pegawai.status-aktifitas.store', $pegawai->encrypted_pegawai_id) }}"
    method="POST"
    submitText="Ajukan Perubahan"
>
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex">
            <div>
                <i class="ti ti-info-circle fs-2 me-2"></i>
            </div>
            <div>
                <h4 class="alert-title">Informasi Status</h4>
                <div class="text-muted">
                    Status Aktifitas saat ini: <strong>{{ $pegawai->latestStatusAktifitas->statusAktifitas->nama_status ?? 'Belum ada' }}</strong><br>
                    Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="statusaktifitas_id" label="Status Aktifitas Baru" required="true">
                <option value="">Pilih Status</option>
                @foreach($statusAktifitas as $status)
                    <option value="{{ $status->statusaktifitas_id }}">{{ $status->nama_status }} ({{ $status->kode_status }})</option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tmt" label="TMT (Terhitung Mulai Tanggal)" required="true" />
        </div>
        
        <div class="col-12">
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" />
        </div>
    </div>
</x-tabler.form-modal>
