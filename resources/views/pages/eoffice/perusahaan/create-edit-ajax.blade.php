@php
    $modelExists = isset($perusahaan) && $perusahaan->exists;
@endphp

<x-tabler.form-modal
    title="{{ $modelExists ? 'Edit Perusahaan' : 'Tambah Perusahaan' }}"
    route="{{ $modelExists ? route('eoffice.perusahaan.update', $perusahaan->encrypted_perusahaan_id) : route('eoffice.perusahaan.store') }}"
    method="{{ $modelExists ? 'PUT' : 'POST' }}"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="kategoriperusahaan_id" label="Kategori Perusahaan" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategoriperusahaan_id }}" {{ ($perusahaan->kategoriperusahaan_id ?? '') == $cat->kategoriperusahaan_id ? 'selected' : '' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="nama_perusahaan" label="Nama Perusahaan" value="{{ $perusahaan->nama_perusahaan ?? '' }}" placeholder="Masukkan nama perusahaan" required />
        </div>
    </div>
    
    <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" value="{{ $perusahaan->alamat ?? '' }}" placeholder="Alamat lengkap" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input name="kota" label="Kota" value="{{ $perusahaan->kota ?? '' }}" placeholder="Kota" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="telp" label="Telepon" value="{{ $perusahaan->telp ?? '' }}" placeholder="No. Telepon" />
        </div>
    </div>
</x-tabler.form-modal>
