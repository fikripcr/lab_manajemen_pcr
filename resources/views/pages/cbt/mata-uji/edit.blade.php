<form action="{{ route('cbt.mata-uji.update', $mu->hashid) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Mata Uji</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <x-tabler.form-input name="nama_mata_uji" label="Nama Mata Uji" value="{{ $mu->nama_mata_uji }}" required="true" />
        <div class="mb-3">
            <label class="form-label required">Tipe</label>
            <select name="tipe" class="form-select" required>
                <option value="PMB" {{ $mu->tipe == 'PMB' ? 'selected' : '' }}>PMB (Penerimaan Mahasiswa Baru)</option>
                <option value="Akademik" {{ $mu->tipe == 'Akademik' ? 'selected' : '' }}>Akademik (UTS/UAS)</option>
            </select>
        </div>
        <x-tabler.form-textarea name="deskripsi" label="Deskripsi" value="{{ $mu->deskripsi }}" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Update</button>
    </div>
</form>
