<div class="modal-header">
    <h5 class="modal-title">Edit Status Pegawai</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.status-pegawai.update', $statusPegawai) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <x-tabler.form-input name="kode_status" label="Kode Status" value="{{ $statusPegawai->kode_status }}" maxlength="10" required="true" />
            </div>
            <div class="col-md-8 mb-3">
                <x-tabler.form-input name="nama_status" label="Nama Status" value="{{ $statusPegawai->nama_status }}" required="true" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-input name="organisasi" label="Organisasi" value="{{ $statusPegawai->organisasi }}" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-checkbox name="is_active" value="1" label="Aktif" :checked="$statusPegawai->is_active" switch />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
