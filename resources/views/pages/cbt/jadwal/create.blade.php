<form action="{{ route('cbt.jadwal.store') }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Jadwal Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" placeholder="Contoh: Ujian Masuk Gelombang 1" required="true" />
        
        <div class="mb-3">
            <label class="form-label required">Paket Ujian</label>
            <select name="paket_id" class="form-select" required>
                @foreach($paket as $p)
                    <option value="{{ $p->hashid }}">{{ $p->nama_paket }} ({{ $p->tipe_paket }})</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-input name="waktu_mulai" label="Waktu Mulai" type="datetime-local" required="true" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input name="waktu_selesai" label="Waktu Selesai" type="datetime-local" required="true" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</form>
