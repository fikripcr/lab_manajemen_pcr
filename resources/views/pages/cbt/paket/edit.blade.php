<form action="{{ route('cbt.paket.update', $paket->hashid) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Paket Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <x-tabler.form-input name="nama_paket" label="Nama Paket" value="{{ $paket->nama_paket }}" required="true" />
        <div class="mb-3">
            <label class="form-label required">Tipe Paket</label>
            <select name="tipe_paket" class="form-select" required>
                <option value="PMB" {{ $paket->tipe_paket == 'PMB' ? 'selected' : '' }}>PMB (Penerimaan Mahasiswa Baru)</option>
                <option value="Akademik" {{ $paket->tipe_paket == 'Akademik' ? 'selected' : '' }}>Akademik (UTS/UAS)</option>
            </select>
        </div>
        <x-tabler.form-input name="total_durasi_menit" label="Durasi (Menit)" type="number" value="{{ $paket->total_durasi_menit }}" required="true" />
        <x-tabler.form-input name="kk_nilai_minimal" label="Passing Grade (Minimal Nilai)" type="number" value="{{ $paket->kk_nilai_minimal }}" />
        
        <div class="mt-3">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_acak_soal" value="1" {{ $paket->is_acak_soal ? 'checked' : '' }}>
                <span class="form-check-label">Acak Soal</span>
            </label>
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_acak_opsi" value="1" {{ $paket->is_acak_opsi ? 'checked' : '' }}>
                <span class="form-check-label">Acak Opsi Jawaban</span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Update</button>
    </div>
</form>
