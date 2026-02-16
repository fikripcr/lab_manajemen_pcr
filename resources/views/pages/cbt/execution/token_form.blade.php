<form action="{{ route('cbt.execute.validate-token', $jadwal->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Validasi Token Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="text-center mb-4">
            <div class="avatar avatar-xl mb-3 bg-blue-lt">
                <i class="ti ti-lock-access ti-lg"></i>
            </div>
            <h3>{{ $jadwal->nama_kegiatan }}</h3>
            <p class="text-muted">Masukkan 6 digit token untuk memulai pengerjaan soal.</p>
        </div>
        
        <div class="mb-3 text-center">
            <input type="text" name="token_ujian" class="form-control form-control-lg text-center fw-bold" maxlength="6" placeholder="******" style="font-size: 2rem; letter-spacing: 5px;" required autofocus>
        </div>

        <div class="alert alert-info py-2 small">
            <i class="ti ti-info-circle"></i> Token dapat dilihat di Kartu Ujian atau tanyakan kepada Pengawas.
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Mulai Ujian</button>
    </div>
</form>
