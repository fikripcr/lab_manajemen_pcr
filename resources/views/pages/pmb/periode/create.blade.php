<form action="{{ route('pmb.periode.store') }}" method="POST" class="ajax-form" data-table="#table-periode">
    @csrf
    <x-tabler.form-input name="nama_periode" label="Nama Periode" placeholder="Contoh: 2025/2026 Ganjil" required="true" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" required="true" />
        </div>
    </div>

    <x-tabler.form-checkbox name="is_aktif" label="Set sebagai Periode Aktif" checked="true" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
