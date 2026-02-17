<div class="p-3 border rounded bg-light">
    @if($pendaftaran->status_terkini == 'Draft')
        <p class="mb-3">Silakan lakukan pembayaran pendaftaran untuk melanjutkan.</p>
        <a href="{{ route('pmb.camaba.payment') }}" class="btn btn-success">
            <i class="ti ti-wallet"></i> Ke Pembayaran
        </a>
    @elseif($pendaftaran->status_terkini == 'Menunggu_Verifikasi_Bayar')
        <div class="text-warning mb-2"><i class="ti ti-clock ti-lg"></i></div>
        <p>Menunggu verifikasi pembayaran oleh Admin.</p>
    @elseif($pendaftaran->status_terkini == 'Menunggu_Verifikasi_Berkas')
        <p class="mb-3">Pembayaran terverifikasi. Silakan unggah dokumen persyaratan.</p>
        <a href="{{ route('pmb.camaba.upload') }}" class="btn btn-info">
            <i class="ti ti-upload"></i> Unggah Berkas
        </a>
    @elseif($pendaftaran->status_terkini == 'Siap_Ujian' || $pendaftaran->status_terkini == 'Sedang_Ujian')
        <div class="text-info mb-2"><i class="ti ti-id"></i></div>
        <p>Berkas terverifikasi. Silakan cetak kartu ujian atau mulai ujian jika jadwal sudah aktif.</p>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('pmb.camaba.exam-card') }}" class="btn btn-outline-primary">
                <i class="ti ti-printer"></i> Cetak Kartu Ujian
            </a>
            @if($activeJadwal)
                <button type="button" class="btn btn-success btn-lg ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Validasi Token Ujian" data-url="{{ route('cbt.execute.token-form', $activeJadwal->encrypted_id) }}">
                    <i class="ti ti-player-play"></i> MULAI UJIAN SEKARANG
                </button>
            @endif
        </div>
    @elseif($pendaftaran->status_terkini == 'Sudah_Ujian')
        <div class="text-success mb-2"><i class="ti ti-check-circle ti-lg"></i></div>
        <p class="mb-0">Ujian Anda telah selesai dan sedang dalam proses penilaian.</p>
        <p class="small text-muted">Hasil pengumuman akan diinformasikan melalui dashboard ini atau email resmi.</p>
    @elseif($pendaftaran->status_terkini == 'Lulus')
        <div class="text-success mb-2"><i class="ti ti-confetti ti-lg"></i></div>
        <h3>Selamat! Anda Lulus</h3>
        <p>Silakan lakukan daftar ulang sesuai instruksi di email atau dashboard ini.</p>
    @elseif($pendaftaran->status_terkini == 'Tidak_Lulus')
        <div class="text-danger mb-2"><i class="ti ti-mood-sad ti-lg"></i></div>
        <p>Mohon maaf, Anda belum lulus seleksi periode ini. Tetap semangat!</p>
    @else
        <p>Status: {{ str_replace('_', ' ', $pendaftaran->status_terkini) }}</p>
    @endif
</div>
