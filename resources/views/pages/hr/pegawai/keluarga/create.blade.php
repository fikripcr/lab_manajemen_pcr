<form action="{{ route('hr.pegawai.keluarga.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Data keluarga yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Hubungan</label>
                <select class="form-select" name="hubungan" required>
                    <option value="">Pilih Hubungan</option>
                    <option value="Suami">Suami</option>
                    <option value="Istri">Istri</option>
                    <option value="Anak">Anak</option>
                    <option value="Orang Tua">Orang Tua</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenis Kelamin</label>
                <div>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" checked>
                        <span class="form-check-label">Laki-laki</span>
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="P">
                        <span class="form-check-label">Perempuan</span>
                    </label>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tgl_lahir">
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" rows="2"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" class="form-control" name="telp" placeholder="Opsional">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy">Simpan</x-tabler.button>
    </div>
</form>
