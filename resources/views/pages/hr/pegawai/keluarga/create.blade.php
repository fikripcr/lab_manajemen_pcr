<x-tabler.form-modal
    title="Tambah Riwayat Keluarga"
    route="{{ route('hr.pegawai.keluarga.store', $pegawai->encrypted_pegawai_id) }}"
    method="POST"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        Data keluarga yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama" label="Nama Lengkap" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="hubungan" label="Hubungan" required="true">
                <option value="">Pilih Hubungan</option>
                <option value="Suami">Suami</option>
                <option value="Istri">Istri</option>
                <option value="Anak">Anak</option>
                <option value="Orang Tua">Orang Tua</option>
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label required">Jenis Kelamin</label>
            <div>
                <x-tabler.form-radio 
                    name="jenis_kelamin" 
                    label="Laki-laki" 
                    value="L" 
                    checked 
                    class="form-check-inline" 
                />
                <x-tabler.form-radio 
                    name="jenis_kelamin" 
                    label="Perempuan" 
                    value="P" 
                    class="form-check-inline" 
                />
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_lahir" label="Tanggal Lahir" />
        </div>
        
        <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" />

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="telp" label="Nomor Telepon" placeholder="Opsional" />
        </div>
    </div>
</x-tabler.form-modal>
