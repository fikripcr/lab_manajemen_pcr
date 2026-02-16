<form action="{{ route('pmb.jenis-dokumen.update', $jenisDokumen->encrypted_id) }}" method="POST" class="ajax-form" data-table="#table-jenis-dokumen">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_dokumen" label="Nama Dokumen" value="{{ $jenisDokumen->nama_dokumen }}" required="true" />
    <x-tabler.form-input name="tipe_file" label="Tipe File" value="{{ $jenisDokumen->tipe_file }}" />
    <x-tabler.form-input type="number" name="max_size_kb" label="Ukuran Maksimal (KB)" value="{{ $jenisDokumen->max_size_kb }}" required="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
