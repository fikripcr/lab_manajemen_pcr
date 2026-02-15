<form action="{{ route('hr.pegawai.keluarga.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Data keluarga yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="nama" label="Nama Lengkap" required="true" />
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-select name="hubungan" label="Hubungan" required="true">
                    <option value="">Pilih Hubungan</option>
                    <option value="Suami">Suami</option>
                    <option value="Istri">Istri</option>
                    <option value="Anak">Anak</option>
                    <option value="Orang Tua">Orang Tua</option>
                </x-tabler.form-select>
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
            
            <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" />

            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="telp" label="Nomor Telepon" placeholder="Opsional" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy">Simpan</x-tabler.button>
    </div>
</form>
