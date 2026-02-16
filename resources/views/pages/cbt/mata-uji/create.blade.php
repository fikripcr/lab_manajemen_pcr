<form action="{{ route('cbt.mata-uji.store') }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Mata Uji</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <x-tabler.form-input name="nama_mata_uji" label="Nama Mata Uji" placeholder="Contoh: Tes Potensi Akademik" required="true" />
        <div class="mb-3">
            <label class="form-label required">Tipe</label>
            <select name="tipe" class="form-select" required>
                <option value="PMB">PMB (Penerimaan Mahasiswa Baru)</option>
                <option value="Akademik">Akademik (UTS/UAS)</option>
            </select>
        </div>
        <x-tabler.form-textarea name="deskripsi" label="Deskripsi" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
