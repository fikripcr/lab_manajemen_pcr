<div class="modal-header">
    <h5 class="modal-title">Tambah Status Aktifitas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.status-aktifitas.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <x-tabler.form-input name="kode_status" label="Kode Status" placeholder="Contoh: A, B1" maxlength="5" required="true" />
            </div>
            <div class="col-md-8 mb-3">
                <x-tabler.form-input name="nama_status" label="Nama Status" placeholder="Contoh: Aktif, Cuti, Tugas Belajar" required="true" />
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
