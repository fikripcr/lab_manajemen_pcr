<div class="modal-header">
    <h5 class="modal-title">Edit Jenis Indisipliner</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.jenis-indisipliner.update', $jenisIndisipliner->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Nama Jenis Indisipliner</label>
            <input type="text" class="form-control" name="jenis_indisipliner" value="{{ $jenisIndisipliner->jenis_indisipliner }}" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
