<x-tabler.form-modal
    title="Tambah Perusahaan"
    route="{{ route('eoffice.perusahaan.store') }}"
    method="POST"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="kategoriperusahaan_id" label="Kategori Perusahaan" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategoriperusahaan_id }}">{{ $cat->nama_kategori }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="nama_perusahaan" label="Nama Perusahaan" placeholder="Masukkan nama perusahaan" required />
        </div>
    </div>
    
    <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" placeholder="Alamat lengkap" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input name="kota" label="Kota" placeholder="Kota" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="telp" label="Telepon" placeholder="No. Telepon" />
        </div>
    </div>
</x-tabler.form-modal>
