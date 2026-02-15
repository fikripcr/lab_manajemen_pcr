<div class="modal-header">
    <h5 class="modal-title">Tambah Jenis Shift</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jenis-shift.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-tabler.form-input name="jenis_shift" label="Nama Shift" required="true" />
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Batas Awal Masuk</label>
                <input type="time" class="form-control" name="jam_masuk_awal" placeholder="HH:MM" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required">Waktu Masuk</label>
                <input type="time" class="form-control" name="jam_masuk" placeholder="HH:MM" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required">Batas Akhir Masuk</label>
                <input type="time" class="form-control" name="jam_masuk_akhir" placeholder="HH:MM" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label required">Batas Awal Pulang</label>
                <input type="time" class="form-control" name="jam_pulang_awal" placeholder="HH:MM" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required">Waktu Pulang</label>
                <input type="time" class="form-control" name="jam_pulang" placeholder="HH:MM" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required">Batas Akhir Pulang</label>
                <input type="time" class="form-control" name="jam_pulang_akhir" placeholder="HH:MM" required>
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
