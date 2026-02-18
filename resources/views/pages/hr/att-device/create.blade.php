<x-tabler.form-modal
    title="Tambah Mesin Presensi"
    route="{{ route('hr.att-device.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="name" label="Nama Device" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="sn" label="Serial Number" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="ip" label="IP Address" placeholder="192.168.1.201" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="number" name="port" label="Port" value="4370" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" checked switch />
        </div>
    </div>
</x-tabler.form-modal>
