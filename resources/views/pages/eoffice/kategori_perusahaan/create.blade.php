<form action="{{ route('eoffice.kategori-perusahaan.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label required">Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori" required>
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
