<form action="{{ route('hr.pegawai.keluarga.update', [$pegawai->encrypted_pegawai_id, $keluarga->keluarga_id]) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Perubahan data keluarga akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="nama" label="Nama Lengkap" value="{{ $keluarga->nama }}" required="true" />
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-select name="hubungan" label="Hubungan" required="true">
                    <option value="">Pilih Hubungan</option>
                    <option value="Suami" {{ $keluarga->hubungan == 'Suami' ? 'selected' : '' }}>Suami</option>
                    <option value="Istri" {{ $keluarga->hubungan == 'Istri' ? 'selected' : '' }}>Istri</option>
                    <option value="Anak" {{ $keluarga->hubungan == 'Anak' ? 'selected' : '' }}>Anak</option>
                    <option value="Orang Tua" {{ $keluarga->hubungan == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                </x-tabler.form-select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenis Kelamin</label>
                <div>
                    <x-tabler.form-radio 
                        name="jenis_kelamin" 
                        label="Laki-laki" 
                        value="L" 
                        :checked="$keluarga->jenis_kelamin == 'L'" 
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
                <x-tabler.form-input type="date" name="tgl_lahir" label="Tanggal Lahir" value="{{ $keluarga->tgl_lahir }}" />
            </div>
            
            <x-tabler.form-textarea name="alamat" label="Alamat" rows="2" :value="$keluarga->alamat" />

            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="telp" label="Nomor Telepon" value="{{ $keluarga->telp }}" placeholder="Opsional" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Perubahan" />
    </div>
</form>
