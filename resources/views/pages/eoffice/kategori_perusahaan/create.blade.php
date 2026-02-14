<form action="{{ route('eoffice.kategori-perusahaan.store') }}" method="POST">
    @csrf
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" placeholder="Masukkan nama kategori" required />
    
    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
