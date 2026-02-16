<form action="{{ route('pmb.jalur.update', $jalur->encrypted_id) }}" method="POST" class="ajax-form" data-table="#table-jalur">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_jalur" label="Nama Jalur" value="{{ $jalur->nama_jalur }}" required="true" />
    
    <x-tabler.form-input type="number" name="biaya_pendaftaran" label="Biaya Pendaftaran" value="{{ (int)$jalur->biaya_pendaftaran }}" required="true" />

    <x-tabler.form-checkbox name="is_aktif" label="Status Aktif" :checked="$jalur->is_aktif" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
