@php
    $isEdit = isset($statusAktifitas) && $statusAktifitas->exists;
    $title = ($isEdit ? 'Edit' : 'Tambah') . ' Status Aktifitas';
    $route = $isEdit ? route('hr.status-aktifitas.update', $statusAktifitas) : route('hr.status-aktifitas.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input 
                name="kode_status" 
                label="Kode Status" 
                placeholder="Contoh: A, B1" 
                :value="$isEdit ? $statusAktifitas->kode_status : ''"
                maxlength="5" 
                required="true" 
            />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input 
                name="nama_status" 
                label="Nama Status" 
                placeholder="Contoh: Aktif, Cuti, Tugas Belajar" 
                :value="$isEdit ? $statusAktifitas->nama_status : ''"
                required="true" 
            />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                value="1" 
                label="Aktif" 
                :checked="$isEdit ? $statusAktifitas->is_active : true" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
