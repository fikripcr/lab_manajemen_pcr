<x-tabler.form-modal
    title="Tambah Kategori Perusahaan"
    route="{{ route('eoffice.kategori-perusahaan.store') }}"
    method="POST"
>
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" placeholder="Masukkan nama kategori" required />
</x-tabler.form-modal>
