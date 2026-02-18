<x-tabler.form-modal
    title="Tambah Jalur"
    route="{{ route('pmb.jalur.store') }}"
    method="POST"
    data-table="#table-jalur"
>
    <x-tabler.form-input name="nama_jalur" label="Nama Jalur" placeholder="Contoh: Reguler" required="true" />
    
    <x-tabler.form-input type="number" name="biaya_pendaftaran" label="Biaya Pendaftaran" placeholder="0" required="true" />

    <x-tabler.form-checkbox name="is_aktif" label="Status Aktif" checked="true" />
</x-tabler.form-modal>
