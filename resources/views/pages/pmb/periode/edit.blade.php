<form action="{{ route('pmb.periode.update', $periode->encrypted_id) }}" method="POST" class="ajax-form" data-table="#table-periode">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_periode" label="Nama Periode" value="{{ $periode->nama_periode }}" required="true" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" value="{{ $periode->tanggal_mulai }}" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" value="{{ $periode->tanggal_selesai }}" required="true" />
        </div>
    </div>

    <x-tabler.form-checkbox name="is_aktif" label="Status Aktif" :checked="$periode->is_aktif" />

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
