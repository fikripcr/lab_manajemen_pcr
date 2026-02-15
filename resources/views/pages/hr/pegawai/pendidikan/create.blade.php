<form action="{{ route('hr.pegawai.pendidikan.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Data pendidikan yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenjang Pendidikan</label>
                <select class="form-select" name="jenjang_pendidikan" required>
                    <option value="">Pilih Jenjang</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Perguruan Tinggi</label>
                <input type="text" class="form-control" name="nama_pt" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Bidang Ilmu / Jurusan</label>
                <input type="text" class="form-control" name="bidang_ilmu">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Ijazah (Lulus)</label>
                <input type="date" class="form-control" name="tgl_ijazah" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kota Asal PT</label>
                <input type="text" class="form-control" name="kotaasal_pt">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Negara Asal PT</label>
                <input type="text" class="form-control" name="kodenegara_pt" placeholder="Indonesia">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy">Simpan</x-tabler.button>
    </div>
</form>
