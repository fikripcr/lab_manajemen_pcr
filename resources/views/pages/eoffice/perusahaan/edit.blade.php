<x-tabler.form-modal
    title="Edit Perusahaan"
    route="{{ route('eoffice.perusahaan.update', $perusahaan->perusahaan_id) }}"
    method="PUT"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="kategoriperusahaan_id" label="Kategori Perusahaan" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategoriperusahaan_id }}" {{ $perusahaan->kategoriperusahaan_id == $cat->kategoriperusahaan_id ? 'selected' : '' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="nama_perusahaan" label="Nama Perusahaan" value="{{ $perusahaan->nama_perusahaan }}" required />
        </div>
    </div>
    
    <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" value="{{ $perusahaan->alamat }}" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input name="kota" label="Kota" value="{{ $perusahaan->kota }}" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="telp" label="Telepon" value="{{ $perusahaan->telp }}" />
        </div>
    </div>
</x-tabler.form-modal>
