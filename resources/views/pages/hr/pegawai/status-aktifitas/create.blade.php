<form action="{{ route('hr.pegawai.status-aktifitas.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            Status Aktifitas saat ini: <strong>{{ $pegawai->latestStatusAktifitas->statusAktifitas->nama_status ?? 'Belum ada' }}</strong><br>
            Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
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
            
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Ajukan Perubahan" />
    </div>
</form>
