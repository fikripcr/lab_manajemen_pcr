<x-tabler.form-modal
    title="Edit Kategori Perusahaan"
    route="{{ route('eoffice.kategori-perusahaan.update', $kategori->kategoriperusahaan_id) }}"
    method="PUT"
>
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" value="{{ $kategori->nama_kategori }}" placeholder="Masukkan nama kategori" required />
</x-tabler.form-modal>
