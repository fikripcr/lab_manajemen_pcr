<div class="modal-header">
    <h5 class="modal-title">Edit Status Aktifitas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.status-aktifitas.update', $statusAktifitas) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label required">Kode</label>
                <input type="text" class="form-control" name="kode_status" value="{{ $statusAktifitas->kode_status }}" maxlength="5" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label required">Nama Status Aktifitas</label>
                <input type="text" class="form-control" name="nama_status" value="{{ $statusAktifitas->nama_status }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $statusAktifitas->is_active ? 'checked' : '' }}>
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
