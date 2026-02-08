<form action="{{ route('hr.pegawai.inpassing.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label required">Golongan Inpassing</label>
                <select name="gol_inpassing_id" class="form-select form-control" required>
                    <option value="">Pilih Golongan</option>
                    @foreach($golongan as $g)
                        <option value="{{ $g->gol_inpassing_id }}">{{ $g->golongan }} - {{ $g->nama_pangkat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Terhitung Mulai Tanggal (TMT)</label>
                <input type="date" name="tmt" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Nomor SK</label>
                <input type="text" name="no_sk" class="form-control" placeholder="Nomor SK" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal SK</label>
                <input type="date" name="tgl_sk" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Gaji Pokok</label>
                <input type="number" name="gaji_pokok" class="form-control" placeholder="Contoh: 3000000">
            </div>
             <div class="col-md-6 mb-3">
                <label class="form-label">Masa Kerja (Tahun)</label>
                <input type="number" name="masa_kerja_tahun" class="form-control" placeholder="Tahun">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Masa Kerja (Bulan)</label>
                <input type="number" name="masa_kerja_bulan" class="form-control" placeholder="Bulan">
            </div>
             <div class="col-md-6 mb-3">
                <label class="form-label">Angka Kredit</label>
                <input type="number" step="0.01" name="angka_kredit" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">File SK (PDF/Gambar max 2MB)</label>
                <input type="file" name="file_sk" class="form-control">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
