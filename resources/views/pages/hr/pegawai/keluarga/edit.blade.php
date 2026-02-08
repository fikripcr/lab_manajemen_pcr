<form action="{{ route('hr.pegawai.keluarga.update', [$pegawai->encrypted_pegawai_id, $keluarga->keluarga_id]) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Perubahan data keluarga akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" value="{{ $keluarga->nama }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Hubungan</label>
                <select class="form-select" name="hubungan" required>
                    <option value="">Pilih Hubungan</option>
                    <option value="Suami" {{ $keluarga->hubungan == 'Suami' ? 'selected' : '' }}>Suami</option>
                    <option value="Istri" {{ $keluarga->hubungan == 'Istri' ? 'selected' : '' }}>Istri</option>
                    <option value="Anak" {{ $keluarga->hubungan == 'Anak' ? 'selected' : '' }}>Anak</option>
                    <option value="Orang Tua" {{ $keluarga->hubungan == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenis Kelamin</label>
                <div>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" {{ $keluarga->jenis_kelamin == 'L' ? 'checked' : '' }}>
                        <span class="form-check-label">Laki-laki</span>
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" {{ $keluarga->jenis_kelamin == 'P' ? 'checked' : '' }}>
                        <span class="form-check-label">Perempuan</span>
                    </label>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tgl_lahir" value="{{ $keluarga->tgl_lahir }}">
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" rows="2">{{ $keluarga->alamat }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" class="form-control" name="telp" value="{{ $keluarga->telp }}" placeholder="Opsional">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
        </button>
    </div>
</form>
