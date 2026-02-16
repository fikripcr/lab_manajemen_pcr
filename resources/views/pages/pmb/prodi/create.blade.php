<form action="{{ route('pmb.prodi.store') }}" method="POST" class="ajax-form" data-table="#table-prodi">
    @csrf
    <x-tabler.form-input name="kode_prodi" label="Kode Prodi" placeholder="Contoh: TI" required="true" />
    <x-tabler.form-input name="nama_prodi" label="Nama Prodi" placeholder="Contoh: Teknik Informatika" required="true" />
    <x-tabler.form-input name="fakultas" label="Fakultas" placeholder="Contoh: Teknologi Informasi" />
    <x-tabler.form-input type="number" name="kuota_umum" label="Kuota Umum" value="0" required="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
