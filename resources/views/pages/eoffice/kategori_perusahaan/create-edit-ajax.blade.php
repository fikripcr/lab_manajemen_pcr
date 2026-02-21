@php
    $modelExists = isset($kategori) && $kategori->exists;
@endphp

<x-tabler.form-modal
    title="{{ $modelExists ? 'Edit Kategori Perusahaan' : 'Tambah Kategori Perusahaan' }}"
    route="{{ $modelExists ? route('eoffice.kategori-perusahaan.update', $kategori->encrypted_kategoriperusahaan_id) : route('eoffice.kategori-perusahaan.store') }}"
    method="{{ $modelExists ? 'PUT' : 'POST' }}"
>
    <x-tabler.form-input name="nama_kategori" label="Nama Kategori" value="{{ $kategori->nama_kategori ?? '' }}" placeholder="Contoh: BUMN, Swasta, dsb." required />
    <x-tabler.form-textarea name="keterangan" label="Keterangan" value="{{ $kategori->keterangan ?? '' }}" rows="3" />
</x-tabler.form-modal>
