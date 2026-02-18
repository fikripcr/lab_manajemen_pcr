<x-tabler.form-modal
    title="Edit Jalur"
    route="{{ route('pmb.jalur.update', $jalur->encrypted_id) }}"
    method="PUT"
    data-table="#table-jalur"
    submitText="Simpan Perubahan"
>
    <x-tabler.form-input name="nama_jalur" label="Nama Jalur" value="{{ $jalur->nama_jalur }}" required="true" />
    
    <x-tabler.form-input type="number" name="biaya_pendaftaran" label="Biaya Pendaftaran" value="{{ (int)$jalur->biaya_pendaftaran }}" required="true" />

    <x-tabler.form-checkbox name="is_aktif" label="Status Aktif" :checked="$jalur->is_aktif" />
</x-tabler.form-modal>
