<x-tabler.form-modal
    title="Tambah Status Aktifitas"
    route="{{ route('hr.status-aktifitas.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_status" label="Kode Status" placeholder="Contoh: A, B1" maxlength="5" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="nama_status" label="Nama Status" placeholder="Contoh: Aktif, Cuti, Tugas Belajar" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" checked switch />
        </div>
    </div>
</x-tabler.form-modal>
