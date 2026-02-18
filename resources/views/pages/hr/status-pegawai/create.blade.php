<x-tabler.form-modal
    title="Tambah Status Pegawai"
    route="{{ route('hr.status-pegawai.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_status" label="Kode Status" placeholder="Contoh: A, C, TB" maxlength="10" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="nama_status" label="Nama Status" placeholder="Contoh: Aktif, Cuti, Tugas Belajar" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="organisasi" label="Organisasi" placeholder="Nama organisasi/instansi (jika ada)" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" checked switch />
        </div>
    </div>
</x-tabler.form-modal>
