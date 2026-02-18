<x-tabler.form-modal
    title="Edit Jenis Dokumen"
    route="{{ route('pmb.jenis-dokumen.update', $jenisDokumen->encrypted_id) }}"
    method="PUT"
    data-table="#table-jenis-dokumen"
    submitText="Simpan Perubahan"
>
    <x-tabler.form-input name="nama_dokumen" label="Nama Dokumen" value="{{ $jenisDokumen->nama_dokumen }}" required="true" />
    <x-tabler.form-input name="tipe_file" label="Tipe File" value="{{ $jenisDokumen->tipe_file }}" />
    <x-tabler.form-input type="number" name="max_size_kb" label="Ukuran Maksimal (KB)" value="{{ $jenisDokumen->max_size_kb }}" required="true" />
</x-tabler.form-modal>
