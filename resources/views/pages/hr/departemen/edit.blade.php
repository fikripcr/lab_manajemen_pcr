<div class="modal-header">
    <h5 class="modal-title">Edit Departemen</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.departemen.update', $departemen->departemen_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label required">Nama Departemen</label>
                <input type="text" class="form-control" name="departemen" value="{{ $departemen->departemen }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Singkatan (Abbr)</label>
                <input type="text" class="form-control" name="abbr" value="{{ $departemen->abbr }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Alias</label>
                <input type="text" class="form-control" name="alias" value="{{ $departemen->alias }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $departemen->is_active ? 'checked' : '' }}>
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
