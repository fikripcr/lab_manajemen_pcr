@php($isEdit = isset($disposisi))
<x-tabler.form-modal
    title="{{ $isEdit ? 'Edit Alur Disposisi' : 'Tambah Alur Disposisi' }}"
    route="{{ $isEdit ? route('eoffice.jenis-layanan.disposisi.update', [$jenisLayanan->hashid, $disposisi->hashid]) : route('eoffice.jenis-layanan.disposisi.store', $jenisLayanan->hashid) }}"
    method="{{ $isEdit ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input name="nama_disposisi" label="Nama Langkah / Alur" value="{{ $disposisi->nama_disposisi ?? '' }}" required="true" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="keterangan" label="Petunjuk / Keterangan" value="{{ $disposisi->keterangan ?? '' }}" />
    </div>
    <div class="mb-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_notify_email" value="1" {{ ($disposisi->is_notify_email ?? false) ? 'checked' : '' }}>
            <span class="form-check-label">Kirim Notifikasi Email ke PIC saat langkah ini aktif?</span>
        </label>
    </div>
</x-tabler.form-modal>
