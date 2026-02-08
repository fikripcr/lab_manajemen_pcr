<div class="modal-header">
    <h5 class="modal-title">Tambah Mesin Presensi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.att-device.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label required">Nama Mesin</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label required">Serial Number (SN)</label>
                <input type="text" class="form-control" name="sn" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">IP Address</label>
                <input type="text" class="form-control" name="ip" placeholder="192.168.1.201" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Port</label>
                <input type="number" class="form-control" name="port" value="4370" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <span class="form-check-label">Aktif</span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
