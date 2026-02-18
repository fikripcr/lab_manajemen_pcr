<x-tabler.form-modal
    title="Edit Mesin Presensi"
    route="{{ route('hr.att-device.update', $attDevice->att_device_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="name" label="Nama Device" value="{{ $attDevice->name }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="sn" label="Serial Number" value="{{ $attDevice->sn }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="ip" label="IP Address" value="{{ $attDevice->ip }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="number" name="port" label="Port" value="{{ $attDevice->port }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$attDevice->is_active" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
