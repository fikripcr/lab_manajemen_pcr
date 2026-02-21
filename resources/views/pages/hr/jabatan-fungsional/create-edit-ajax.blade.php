@php
    $isEdit = $jabatanFungsional->exists;
    $title  = $isEdit ? 'Edit Jabatan Fungsional' : 'Tambah Jabatan Fungsional';
    $route  = $isEdit 
        ? route('hr.jabatan-fungsional.update', $jabatanFungsional->hashid) 
        : route('hr.jabatan-fungsional.store');
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
            <x-tabler.form-input name="kode_jabatan" label="Kode Jabatan" :value="$jabatanFungsional->kode_jabatan" placeholder="Contoh: AA" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="jabfungsional" label="Jabatan Fungsional" :value="$jabatanFungsional->jabfungsional" placeholder="Contoh: Asisten Ahli" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input type="number" name="tunjangan" label="Tunjangan" :value="$jabatanFungsional->tunjangan" placeholder="0" prefix="Rp" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$jabatanFungsional->is_active ?? true" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
