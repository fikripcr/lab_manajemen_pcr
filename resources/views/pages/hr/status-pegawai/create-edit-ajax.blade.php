@php
    $item = $statusPegawai ?? new \stdClass();
    $method = isset($statusPegawai) ? 'PUT' : 'POST';
    $route = isset($statusPegawai) ? route('hr.status-pegawai.update', $statusPegawai) : route('hr.status-pegawai.store');
    $title = isset($statusPegawai) ? 'Edit Status Pegawai' : 'Tambah Status Pegawai';
    $submitText = isset($statusPegawai) ? 'Simpan Perubahan' : 'Simpan';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$submitText"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input 
                name="kode_status" 
                label="Kode Status" 
                placeholder="Contoh: A, C, TB" 
                maxlength="10" 
                :value="old('kode_status', $item->kode_status ?? '')"
                required="true" 
            />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input 
                name="nama_status" 
                label="Nama Status" 
                placeholder="Contoh: Aktif, Cuti, Tugas Belajar" 
                :value="old('nama_status', $item->nama_status ?? '')"
                required="true" 
            />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input 
                name="organisasi" 
                label="Organisasi" 
                placeholder="Nama organisasi/instansi (jika ada)" 
                :value="old('organisasi', $item->organisasi ?? '')"
            />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                value="1" 
                label="Aktif" 
                :checked="old('is_active', $item->is_active ?? true)"
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
