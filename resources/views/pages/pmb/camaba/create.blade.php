<x-tabler.form-modal
    :action="route('pmb.camaba.store')"
    method="POST"
    modal-title="Tambah Camaba"
    submit-text="Simpan"
>
    <x-tabler.form-input name="nik" label="NIK" value="{{ old('nik') }}" required="true" />
    <x-tabler.form-input name="no_hp" label="No HP" value="{{ old('no_hp') }}" required="true" />
    <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" value="{{ old('tempat_lahir') }}" required="true" />
    <x-tabler.form-input name="tanggal_lahir" label="Tanggal Lahir" type="date" value="{{ old('tanggal_lahir') }}" required="true" />
    <x-tabler.form-select name="jenis_kelamin" label="Jenis Kelamin" required="true">
        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
    </x-tabler.form-select>
    <x-tabler.form-textarea name="alamat_lengkap" label="Alamat Lengkap" required="true">{{ old('alamat_lengkap') }}</x-tabler.form-textarea>
    <x-tabler.form-input name="asal_sekolah" label="Asal Sekolah" value="{{ old('asal_sekolah') }}" required="true" />
    <x-tabler.form-input name="nisn" label="NISN" value="{{ old('nisn') }}" />
    <x-tabler.form-input name="nama_ibu_kandung" label="Nama Ibu Kandung" value="{{ old('nama_ibu_kandung') }}" />
</x-tabler.form-modal>
