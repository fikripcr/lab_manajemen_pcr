<div class="modal-header">
    <h5 class="modal-title">Edit Jabatan Fungsional</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jabatan-fungsional.update', $jabatanFungsional) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <x-tabler.form-input name="kode_jabatan" label="Kode Jabatan" value="{{ $jabatanFungsional->kode_jabatan }}" required="true" />
            </div>
            <div class="col-md-8 mb-3">
                <x-tabler.form-input name="jabfungsional" label="Jabatan Fungsional" value="{{ $jabatanFungsional->jabfungsional }}" required="true" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-input type="number" name="tunjangan" label="Tunjangan" :value="$jabatanFungsional->tunjangan" prefix="Rp" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-checkbox 
                    name="is_active" 
                    label="Aktif" 
                    value="1" 
                    :checked="$jabatanFungsional->is_active" 
                    switch 
                />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
