<div class="modal-header">
    <h5 class="modal-title">Edit Jenis Shift</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jenis-shift.update', $jenisShift) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-tabler.form-input name="jenis_shift" label="Nama Shift" value="{{ $jenisShift->jenis_shift }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="time" name="jam_masuk" label="Jam Masuk" value="{{ $jenisShift->jam_masuk }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="time" name="jam_pulang" label="Jam Pulang" value="{{ $jenisShift->jam_pulang }}" required="true" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-checkbox 
                    name="is_active" 
                    label="Aktif" 
                    value="1" 
                    :checked="$jenisShift->is_active" 
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
