<x-tabler.form-modal
    title="Edit Status Aktifitas"
    route="{{ route('hr.status-aktifitas.update', $statusAktifitas) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_status" label="Kode Status" value="{{ $statusAktifitas->kode_status }}" maxlength="5" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="nama_status" label="Nama Status" value="{{ $statusAktifitas->nama_status }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" :checked="$statusAktifitas->is_active" switch />
        </div>
    </div>
</x-tabler.form-modal>
