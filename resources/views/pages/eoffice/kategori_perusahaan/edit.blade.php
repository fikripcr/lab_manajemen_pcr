<form action="{{ route('eoffice.kategori-perusahaan.update', $kategori->kategoriperusahaan_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label required">Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" placeholder="Masukkan nama kategori" required>
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
