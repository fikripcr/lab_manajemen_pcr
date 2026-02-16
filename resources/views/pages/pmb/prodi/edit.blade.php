<form action="{{ route('pmb.prodi.update', $prodi->encrypted_id) }}" method="POST" class="ajax-form" data-table="#table-prodi">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="kode_prodi" label="Kode Prodi" value="{{ $prodi->kode_prodi }}" required="true" />
    <x-tabler.form-input name="nama_prodi" label="Nama Prodi" value="{{ $prodi->nama_prodi }}" required="true" />
    <x-tabler.form-input name="fakultas" label="Fakultas" value="{{ $prodi->fakultas }}" />
    <x-tabler.form-input type="number" name="kuota_umum" label="Kuota Umum" value="{{ $prodi->kuota_umum }}" required="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
