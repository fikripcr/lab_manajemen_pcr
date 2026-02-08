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
                <label class="form-label required">Nama Mesin</label>
                <input type="text" class="form-control" name="name" value="{{ $attDevice->name }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label required">Serial Number (SN)</label>
                <input type="text" class="form-control" name="sn" value="{{ $attDevice->sn }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">IP Address</label>
                <input type="text" class="form-control" name="ip" value="{{ $attDevice->ip }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Port</label>
                <input type="number" class="form-control" name="port" value="{{ $attDevice->port }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $attDevice->is_active ? 'checked' : '' }}>
                    <span class="form-check-label">Aktif</span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
