<form action="{{ route('eoffice.perusahaan.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label required">Kategori Perusahaan</label>
            <select name="kategoriperusahaan_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategoriperusahaan_id }}">{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label required">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" required>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kota</label>
            <input type="text" name="kota" class="form-control" placeholder="Kota">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telp" class="form-control" placeholder="No. Telepon">
        </div>
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
