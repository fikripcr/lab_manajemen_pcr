<form action="{{ route('hr.pegawai.jabatan-fungsional.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            Jabatan Fungsional saat ini: <strong>{{ $pegawai->latestJabatanFungsional->jabatanFungsional->jabfungsional ?? 'Belum ada' }}</strong><br>
            Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Jabatan Fungsional Baru</label>
                <select class="form-select" name="jabfungsional_id" required>
                    <option value="">Pilih Jabatan</option>
                    @foreach($jabatan as $item)
                        <option value="{{ $item->jabfungsional_id }}">{{ $item->jabfungsional }} ({{ $item->kode }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                <input type="date" class="form-control" name="tmt" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No SK (Internal)</label>
                <input type="text" class="form-control" name="no_sk_internal">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
    </div>
</form>
