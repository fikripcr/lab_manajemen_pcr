<x-tabler.form-modal
    title="Edit Status Pegawai"
    route="{{ route('hr.status-pegawai.update', $statusPegawai) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_status" label="Kode Status" value="{{ $statusPegawai->kode_status }}" maxlength="10" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="nama_status" label="Nama Status" value="{{ $statusPegawai->nama_status }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="organisasi" label="Organisasi" value="{{ $statusPegawai->organisasi }}" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" :checked="$statusPegawai->is_active" switch />
        </div>
    </div>
</x-tabler.form-modal>
