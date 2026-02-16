<form action="{{ route('pmb.jalur.store') }}" method="POST" class="ajax-form" data-table="#table-jalur">
    @csrf
    <x-tabler.form-input name="nama_jalur" label="Nama Jalur" placeholder="Contoh: Reguler" required="true" />
    
    <x-tabler.form-input type="number" name="biaya_pendaftaran" label="Biaya Pendaftaran" placeholder="0" required="true" />

    <x-tabler.form-checkbox name="is_aktif" label="Status Aktif" checked="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
