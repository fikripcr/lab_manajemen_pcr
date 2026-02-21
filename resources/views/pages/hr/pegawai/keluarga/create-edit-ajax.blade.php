@php
    $isEdit = $keluarga->exists;
    $title  = $isEdit ? 'Edit Riwayat Keluarga' : 'Tambah Riwayat Keluarga';
    $route  = $isEdit 
        ? route('hr.pegawai.keluarga.update', [$pegawai->encrypted_pegawai_id, $keluarga->keluarga_id]) 
        : route('hr.pegawai.keluarga.store', $pegawai->encrypted_pegawai_id);
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        {{ $isEdit ? 'Perubahan data' : 'Data' }} keluarga yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama" label="Nama Lengkap" :value="$keluarga->nama" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="hubungan" label="Hubungan" required="true">
                <option value="">Pilih Hubungan</option>
                @foreach(['Suami', 'Istri', 'Anak', 'Orang Tua'] as $hub)
                    <option value="{{ $hub }}" {{ $keluarga->hubungan == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label required">Jenis Kelamin</label>
            <div>
                <x-tabler.form-radio 
                    name="jenis_kelamin" 
                    label="Laki-laki" 
                    value="L" 
                    :checked="!$isEdit || $keluarga->jenis_kelamin == 'L'" 
                    class="form-check-inline" 
                />
                <x-tabler.form-radio 
                    name="jenis_kelamin" 
                    label="Perempuan" 
                    value="P" 
                    :checked="$keluarga->jenis_kelamin == 'P'" 
                    class="form-check-inline" 
                />
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_lahir" label="Tanggal Lahir" :value="$keluarga->tgl_lahir" />
        </div>
        
        <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" :value="$keluarga->alamat" />

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="telp" label="Nomor Telepon" :value="$keluarga->telp" placeholder="Opsional" />
        </div>
    </div>
</x-tabler.form-modal>
