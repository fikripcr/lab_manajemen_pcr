<div class="modal-header">
    <h5 class="modal-title">Edit Jabatan Struktural</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jabatan-struktural.update', $jabatanStruktural) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label required">Nama Jabatan</label>
                <input type="text" class="form-control" name="nama" value="{{ $jabatanStruktural->nama }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Parent (Atasan)</label>
                <select class="form-select" name="parent_id">
                    <option value="">- Pilih Parent -</option>
                    @foreach($parents as $id => $nama)
                        <option value="{{ $id }}" {{ $jabatanStruktural->parent_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $jabatanStruktural->is_active ? 'checked' : '' }}>
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
