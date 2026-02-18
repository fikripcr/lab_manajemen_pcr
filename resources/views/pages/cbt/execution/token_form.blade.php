<x-tabler.form-modal
    title="Validasi Token Ujian"
    route="{{ route('cbt.execute.validate-token', $jadwal->hashid) }}"
    method="POST"
    data-redirect="true"
    submitText="Mulai Ujian"
    submitIcon=""
>
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
</x-tabler.form-modal>
