<form action="{{ route('pmb.jenis-dokumen.store') }}" method="POST" class="ajax-form" data-table="#table-jenis-dokumen">
    @csrf
    <x-tabler.form-input name="nama_dokumen" label="Nama Dokumen" placeholder="Contoh: KTP / Ijazah" required="true" />
    <x-tabler.form-input name="tipe_file" label="Tipe File" placeholder="Contoh: pdf,jpg,png" />
    <x-tabler.form-input type="number" name="max_size_kb" label="Ukuran Maksimal (KB)" value="2048" required="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
