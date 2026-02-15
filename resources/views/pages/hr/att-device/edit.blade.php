<div class="modal-header">
    <h5 class="modal-title">Edit Mesin Presensi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.att-device.update', $attDevice->att_device_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
