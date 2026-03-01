@extends('layouts.assessment.app')

@section('title', $jadwal->nama_kegiatan . ' — Informasi Ujian')

@section('content')
<div class="page page-center min-vh-100 py-5">
    <div class="container-xl">
        
        {{-- Hero Header dengan Dynamic Gradient berdasarkan Primary Color --}}
        <div class="assessment-hero text-white rounded-3 mb-4 p-4 p-md-5" style="background: linear-gradient(135deg, var(--tblr-primary, #206bc4) 0%, var(--tblr-primary-dark, #1e293b) 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-white text-primary fs-6 px-3 py-2">
                            <i class="ti ti-school me-1"></i>Computer Based Test
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold mb-2">{{ $jadwal->nama_kegiatan }}</h1>
                    <p class="lead mb-0 opacity-75">
                        Selamat datang, <strong class="text-white">{{ auth()->user()->name }}</strong>. 
                        Pastikan Anda membaca seluruh informasi sebelum memulai ujian.
                    </p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <i class="ti ti-certificate" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Left Column - Info Cards --}}
            <div class="col-lg-7">
                {{-- Stats Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-file-text text-primary fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-primary fw-bold">{{ $totalSoal }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Total Soal</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-clock text-primary fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-primary fw-bold">{{ round($durasi / 60, 1) }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Jam</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-calendar-time text-primary fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-primary fw-bold">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Mulai</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Exam Details --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h3 class="card-title text-uppercase small text-muted fw-bold mb-0">
                            <i class="ti ti-info-circle me-2"></i>Detail Ujian
                        </h3>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-borderless table-vcenter mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-package me-2"></i>Paket Soal
                                        </td>
                                        <td class="fw-semibold py-3 text-end">{{ $jadwal->paket->nama_paket }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-calendar-event me-2"></i>Tanggal
                                        </td>
                                        <td class="fw-semibold py-3 text-end">{{ $jadwal->waktu_mulai->format('d F Y') }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-clock-2 me-2"></i>Waktu
                                        </td>
                                        <td class="fw-semibold py-3 text-end">
                                            {{ $jadwal->waktu_mulai->format('H:i') }} - {{ $jadwal->waktu_selesai->format('H:i') }} WIB
                                        </td>
                                    </tr>
                                    @if($jadwal->paket->is_acak_soal)
                                    <tr>
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-arrows-shuffle me-2"></i>Pengacakan
                                        </td>
                                        <td class="py-3 text-end">
                                            <span class="badge bg-warning-lt text-warning px-3 py-2">
                                                <i class="ti ti-shuffle me-1"></i>Soal Diacak
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Rules & Action --}}
            <div class="col-lg-5">
                {{-- Tata Tertib --}}
                <div class="card border-0 shadow-sm mb-4 bg-azure-lt">
                    <div class="card-body p-4">
                        <h4 class="card-title text-uppercase small text-azure fw-bold mb-3">
                            <i class="ti ti-info-circle me-2"></i>Tata Tertib Ujian
                        </h4>
                        <ul class="mb-0 ps-3 small">
                            <li class="mb-2">
                                <i class="ti ti-wifi me-2 text-azure"></i>
                                Pastikan koneksi internet Anda <strong>stabil</strong> selama ujian berlangsung.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-device-floppy me-2 text-azure"></i>
                                Jawaban <strong>tersimpan otomatis</strong> setiap kali Anda memilih opsi.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-window me-2 text-azure"></i>
                                <strong>Jangan pindah tab</strong> atau jendela browser saat ujian berlangsung.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-clock me-2 text-azure"></i>
                                Ujian akan <strong>berhenti otomatis</strong> ketika waktu habis.
                            </li>
                            <li>
                                <i class="ti ti-check me-2 text-azure"></i>
                                Klik <strong>Selesaikan Ujian</strong> setelah menjawab semua soal.
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Warning jika dilanjutkan --}}
                @if($existing && $existing->status === 'Sedang_Mengerjakan')
                <div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">
                    <i class="ti ti-alert-triangle fs-3"></i>
                    <div>
                        <strong>Sesi Dilanjutkan</strong><br>
                        <span class="small">Anda sebelumnya sudah memulai ujian ini. Klik tombol di bawah untuk melanjutkan dari posisi terakhir.</span>
                    </div>
                </div>
                @endif

                {{-- Action Button --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('cbt.execute.start', $jadwal->hashid) }}" method="POST">
                            @csrf
                            <x-tabler.button type="submit" class="btn-primary btn-lg w-100 py-3 mb-3" icon="ti ti-player-play" text="{{ ($existing && $existing->status === 'Sedang_Mengerjakan') ? 'Lanjutkan Ujian' : 'Mulai Ujian Sekarang' }}" />
                        </form>
                        
                        @if(auth()->user()->hasRole('admin'))
                        <hr class="my-3">
                        <div class="d-grid">
                            <a href="{{ route('cbt.execute.monitor', $jadwal->hashid) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-chart-bar me-1"></i>Monitoring Ujian
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add animation on load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush

@endsection
