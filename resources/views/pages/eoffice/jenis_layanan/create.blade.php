<form action="{{ route('eoffice.jenis-layanan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label required">Nama Layanan</label>
        <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Surat Keterangan Mahasiswa Aktif" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label required">Kategori</label>
            <select name="kategori" class="form-select" required>
                <option value="layanan">Layanan Umum</option>
                <option value="umum">Administrasi Umum</option>
                <option value="akademik">Akademik</option>
                <option value="keuangan">Keuangan</option>
                <option value="sdm">SDM</option>
                <option value="sarpras">Sarpras</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label required">Batas Pengerjaan (Jam)</label>
            <input type="number" name="batas_pengerjaan" class="form-control" value="24" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Tampilkan Untuk</label>
        <div class="form-selectgroup">
            <label class="form-selectgroup-item">
                <input type="checkbox" name="only_show_on[]" value="Pegawai" class="form-selectgroup-input" checked>
                <span class="form-selectgroup-label">Pegawai</span>
            </label>
            <label class="form-selectgroup-item">
                <input type="checkbox" name="only_show_on[]" value="Mahasiswa" class="form-selectgroup-input" checked>
                <span class="form-selectgroup-label">Mahasiswa</span>
            </label>
            <label class="form-selectgroup-item">
                <input type="checkbox" name="only_show_on[]" value="Dosen" class="form-selectgroup-input" checked>
                <span class="form-selectgroup-label">Dosen</span>
            </label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Template Word (.docx)</label>
        <input type="file" name="file_template" class="form-control" accept=".docx">
        <small class="text-muted">Upload template jika layanan ini memerlukan generate dokumen otomatis.</small>
    </div>

    <div class="divider">Fitur Tambahan</div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_diskusi" value="1">
                <span class="form-check-label">Aktifkan Diskusi</span>
            </label>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_disposisi" value="1" checked>
                <span class="form-check-label">Aktifkan Disposisi</span>
            </label>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_feedback" value="1" checked>
                <span class="form-check-label">Aktifkan Feedback</span>
            </label>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <span class="form-check-label">Layanan Aktif</span>
        </label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
