<form action="{{ route('pmb.sesi-ujian.store') }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Sesi Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Periode</label>
            <select name="periode_id" class="form-select" required>
                @foreach($periode as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
                @endforeach
            </select>
        </div>
        <x-tabler.form-input name="nama_sesi" label="Nama Sesi" placeholder="Contoh: Sesi 1 - Gelombang 1" required="true" />
        
        <div class="row">
            <div class="col-6">
                <x-tabler.form-input type="datetime-local" name="waktu_mulai" label="Waktu Mulai" required="true" />
            </div>
            <div class="col-6">
                <x-tabler.form-input type="datetime-local" name="waktu_selesai" label="Waktu Selesai" required="true" />
            </div>
        </div>

        <x-tabler.form-input name="lokasi" label="Lokasi" placeholder="Contoh: Lab Komputer 1" required="true" />
        <x-tabler.form-input type="number" name="kuota" label="Kuota Peserta" value="30" required="true" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Sesi</button>
    </div>
</form>
