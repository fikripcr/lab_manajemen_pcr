<form action="{{ route('eoffice.kategori-perusahaan.update', $kategori->kategoriperusahaan_id) }}" method="POST">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" value="{{ $kategori->nama_kategori }}" placeholder="Masukkan nama kategori" required />
    
    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
