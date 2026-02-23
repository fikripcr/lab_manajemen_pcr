@php
    $isEdit = isset($mu) && $mu->exists;
    $title  = ($isEdit ? 'Edit' : 'Tambah') . ' Mata Uji';
    $route  = $isEdit ? route('cbt.mata-uji.update', $mu->encrypted_mata_uji_id) : route('cbt.mata-uji.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    data-redirect="true"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <x-tabler.form-input name="nama_mata_uji" label="Nama Mata Uji" placeholder="Contoh: Tes Potensi Akademik"
        :value="$isEdit ? $mu->nama_mata_uji : old('nama_mata_uji')" required="true" />

    <x-tabler.form-select name="tipe" label="Tipe" required="true"
        :options="['PMB' => 'PMB (Penerimaan Mahasiswa Baru)', 'Akademik' => 'Akademik (UTS/UAS)']"
        :selected="$isEdit ? $mu->tipe : old('tipe')"
        placeholder="Pilih Tipe" />


    <x-tabler.form-textarea name="deskripsi" label="Deskripsi"
        :value="$isEdit ? $mu->deskripsi : old('deskripsi')" />
</x-tabler.form-modal>
