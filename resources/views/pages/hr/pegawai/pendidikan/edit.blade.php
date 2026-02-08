<form action="{{ route('hr.pegawai.pendidikan.update', [$pegawai->encrypted_pegawai_id, $pendidikan->riwayatpendidikan_id]) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Perubahan data pendidikan akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenjang Pendidikan</label>
                <select class="form-select" name="jenjang_pendidikan" required>
                    <option value="">Pilih Jenjang</option>
                    <option value="D3" {{ $pendidikan->jenjang_pendidikan == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="D4" {{ $pendidikan->jenjang_pendidikan == 'D4' ? 'selected' : '' }}>D4</option>
                    <option value="S1" {{ $pendidikan->jenjang_pendidikan == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ $pendidikan->jenjang_pendidikan == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ $pendidikan->jenjang_pendidikan == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Perguruan Tinggi</label>
                <input type="text" class="form-control" name="nama_pt" value="{{ $pendidikan->nama_pt }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Bidang Ilmu / Jurusan</label>
                <input type="text" class="form-control" name="bidang_ilmu" value="{{ $pendidikan->bidang_ilmu }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Ijazah (Lulus)</label>
                <input type="date" class="form-control" name="tgl_ijazah" value="{{ $pendidikan->tgl_ijazah ? \Carbon\Carbon::parse($pendidikan->tgl_ijazah)->format('Y-m-d') : '' }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kota Asal PT</label>
                <input type="text" class="form-control" name="kotaasal_pt" value="{{ $pendidikan->kotaasal_pt }}">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Negara Asal PT</label>
                <input type="text" class="form-control" name="kodenegara_pt" value="{{ $pendidikan->kodenegara_pt }}" placeholder="Indonesia">
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
