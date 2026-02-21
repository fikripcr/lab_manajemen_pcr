@php
    $isEdit = $attDevice->exists;
    $title  = $isEdit ? 'Edit Mesin Presensi' : 'Tambah Mesin Presensi';
    $route  = $isEdit 
        ? route('hr.att-device.update', $attDevice->att_device_id) 
        : route('hr.att-device.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="name" label="Nama Device" :value="$attDevice->name" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="sn" label="Serial Number" :value="$attDevice->sn" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="ip" label="IP Address" :value="$attDevice->ip" placeholder="192.168.1.201" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="number" name="port" label="Port" :value="$attDevice->port ?? 4370" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$attDevice->is_active ?? true" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
