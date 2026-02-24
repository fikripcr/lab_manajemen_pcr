<x-tabler.form-modal
    :action="route('pmb.camaba.update', $camaba->encrypted_camaba_id)"
    method="PUT"
    modal-title="Edit Camaba"
    submit-text="Simpan Perubahan"
>
    <x-tabler.form-input name="nik" label="NIK" value="{{ old('nik', $camaba->nik) }}" required="true" />
    <x-tabler.form-input name="no_hp" label="No HP" value="{{ old('no_hp', $camaba->no_hp) }}" required="true" />
    <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" value="{{ old('tempat_lahir', $camaba->tempat_lahir) }}" required="true" />
    <x-tabler.form-input name="tanggal_lahir" label="Tanggal Lahir" type="date" value="{{ old('tanggal_lahir', $camaba->tanggal_lahir) }}" required="true" />
    <x-tabler.form-select name="jenis_kelamin" label="Jenis Kelamin" required="true">
        <option value="L" {{ $camaba->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ $camaba->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
    </x-tabler.form-select>
    <x-tabler.form-textarea name="alamat_lengkap" label="Alamat Lengkap" required="true">{{ old('alamat_lengkap', $camaba->alamat_lengkap) }}</x-tabler.form-textarea>
    <x-tabler.form-input name="asal_sekolah" label="Asal Sekolah" value="{{ old('asal_sekolah', $camaba->asal_sekolah) }}" required="true" />
    <x-tabler.form-input name="nisn" label="NISN" value="{{ old('nisn', $camaba->nisn) }}" />
    <x-tabler.form-input name="nama_ibu_kandung" label="Nama Ibu Kandung" value="{{ old('nama_ibu_kandung', $camaba->nama_ibu_kandung) }}" />
</x-tabler.form-modal>
