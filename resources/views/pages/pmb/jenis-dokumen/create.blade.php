<x-tabler.form-modal
    title="Tambah Jenis Dokumen"
    route="{{ route('pmb.jenis-dokumen.store') }}"
    method="POST"
    data-table="#table-jenis-dokumen"
>
    <x-tabler.form-input name="nama_dokumen" label="Nama Dokumen" placeholder="Contoh: KTP / Ijazah" required="true" />
    <x-tabler.form-input name="tipe_file" label="Tipe File" placeholder="Contoh: pdf,jpg,png" />
    <x-tabler.form-input type="number" name="max_size_kb" label="Ukuran Maksimal (KB)" value="2048" required="true" />
</x-tabler.form-modal>
