<x-tabler.form-modal
    title="Tambah Mata Uji"
    route="{{ route('cbt.mata-uji.store') }}"
    method="POST"
    data-redirect="true"
    submitText="Simpan"
>
    <x-tabler.form-input name="nama_mata_uji" label="Nama Mata Uji" placeholder="Contoh: Tes Potensi Akademik" required="true" />
    <x-tabler.form-select name="tipe" label="Tipe" required="true" 
        :options="['PMB' => 'PMB (Penerimaan Mahasiswa Baru)', 'Akademik' => 'Akademik (UTS/UAS)']" 
        placeholder="Pilih Tipe" />
    <x-tabler.form-textarea name="deskripsi" label="Deskripsi" />
</x-tabler.form-modal>
