<div class="modal-header">
    <h5 class="modal-title">Edit Jenis Indisipliner</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.jenis-indisipliner.update', $jenisIndisipliner->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="jenis_indisipliner" label="Jenis Indisipliner" value="{{ $jenisIndisipliner->jenis_indisipliner }}" required="true" />
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
