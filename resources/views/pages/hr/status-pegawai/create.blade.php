<div class="modal-header">
    <h5 class="modal-title">Tambah Status Pegawai</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.status-pegawai.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label required">Kode Status</label>
                <input type="text" class="form-control" name="kode_status" placeholder="Contoh: P1, P2" maxlength="10" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label required">Nama Status</label>
                <input type="text" class="form-control" name="nama_status" placeholder="Contoh: Pegawai Tetap, Kontrak" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Organisasi</label>
                <input type="text" class="form-control" name="organisasi" placeholder="Opsional">
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
