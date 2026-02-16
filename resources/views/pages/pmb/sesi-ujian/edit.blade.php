<form action="{{ route('pmb.sesi-ujian.update', $sesiUjian->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Sesi Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Periode</label>
            <select name="periode_id" class="form-select" required>
                @foreach($periode as $p)
                    <option value="{{ $p->id }}" {{ $sesiUjian->periode_id == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
        </div>
        <x-tabler.form-input name="nama_sesi" label="Nama Sesi" :value="$sesiUjian->nama_sesi" required="true" />
        
        <div class="row">
            <div class="col-6">
                <x-tabler.form-input type="datetime-local" name="waktu_mulai" label="Waktu Mulai" :value="$sesiUjian->waktu_mulai->format('Y-m-d\TH:i')" required="true" />
            </div>
            <div class="col-6">
                <x-tabler.form-input type="datetime-local" name="waktu_selesai" label="Waktu Selesai" :value="$sesiUjian->waktu_selesai->format('Y-m-d\TH:i')" required="true" />
            </div>
        </div>

        <x-tabler.form-input name="lokasi" label="Lokasi" :value="$sesiUjian->lokasi" required="true" />
        <x-tabler.form-input type="number" name="kuota" label="Kuota Peserta" :value="$sesiUjian->kuota" required="true" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
    </div>
</form>
