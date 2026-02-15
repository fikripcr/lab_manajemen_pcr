<div class="modal-header">
    <h5 class="modal-title">Tambah Jabatan Struktural</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.jabatan-struktural.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-tabler.form-input name="nama" label="Nama Jabatan" required="true" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-select name="parent_id" label="Parent (Atasan)">
                    <option value="">- Pilih Parent -</option>
                    @foreach($parents as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-checkbox 
                    name="is_active" 
                    label="Aktif" 
                    value="1" 
                    checked 
                    switch 
                />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
