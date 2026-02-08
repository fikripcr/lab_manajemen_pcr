<form action="{{ route('hr.pegawai.status-aktifitas.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            Status Aktifitas saat ini: <strong>{{ $pegawai->latestStatusAktifitas->statusAktifitas->status_aktifitas ?? 'Belum ada' }}</strong><br>
            Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Status Aktifitas Baru</label>
                <select class="form-select" name="statusaktifitas_id" required>
                    <option value="">Pilih Status</option>
                    @foreach($statusAktifitas as $status)
                        <option value="{{ $status->statusaktifitas_id }}">{{ $status->status_aktifitas }} ({{ $status->kode }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                <input type="date" class="form-control" name="tmt" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No SK</label>
                <input type="text" class="form-control" name="no_sk">
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
    </div>
</form>
