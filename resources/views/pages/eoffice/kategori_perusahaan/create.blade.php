<form action="{{ route('eoffice.kategori-perusahaan.store') }}" method="POST">
    @csrf
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" placeholder="Masukkan nama kategori" required />
    
    <div class="text-end">
        <x-tabler.button type="button" class="btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary">Simpan</x-tabler.button>
    </div>
</form>
