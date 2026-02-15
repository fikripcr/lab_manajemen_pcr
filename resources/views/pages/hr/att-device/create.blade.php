<div class="modal-header">
    <h5 class="modal-title">Tambah Mesin Presensi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.att-device.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
