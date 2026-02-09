<div class="modal-header">
    <h5 class="modal-title">Edit Jabatan Fungsional</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jabatan-fungsional.update', $jabatanFungsional) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label required">Kode Jabatan</label>
                <input type="text" class="form-control" name="kode_jabatan" value="{{ $jabatanFungsional->kode_jabatan }}" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label required">Nama Jabatan (Jabfungsional)</label>
                <input type="text" class="form-control" name="jabfungsional" value="{{ $jabatanFungsional->jabfungsional }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Tunjangan</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" name="tunjangan" value="{{ $jabatanFungsional->tunjangan }}">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $jabatanFungsional->is_active ? 'checked' : '' }}>
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
