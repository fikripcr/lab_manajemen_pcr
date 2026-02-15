<div class="modal-header">
    <h5 class="modal-title">Tambah Jenis Indisipliner</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.jenis-indisipliner.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="jenis_indisipliner" label="Jenis Indisipliner" required="true" placeholder="Contoh: Teguran Lisan, Surat Peringatan, dll" />
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
