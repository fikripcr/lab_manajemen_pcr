<form action="{{ route('eoffice.kategori-perusahaan.update', $kategori->kategoriperusahaan_id) }}" method="POST">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" value="{{ $kategori->nama_kategori }}" placeholder="Masukkan nama kategori" required />
    
    <div class="text-end">
        <x-tabler.button type="button" class="btn-link link-secondary me-auto" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Simpan Perubahan" />
    </div>
</form>
