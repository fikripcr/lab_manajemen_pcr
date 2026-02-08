<form action="{{ route('hr.pegawai.status-pegawai.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            Status Pegawai saat ini: <strong>{{ $pegawai->latestStatusPegawai->statusPegawai->nama_status ?? 'Belum ada' }}</strong><br>
            Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Status Baru</label>
                <select class="form-select" name="statuspegawai_id" required>
                    <option value="">Pilih Status</option>
                    @foreach($statusPegawai as $status)
                        <option value="{{ $status->statuspegawai_id }}">{{ $status->nama_status }} ({{ $status->kode_status }})</option>
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
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
    </div>
</form>
