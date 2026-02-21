@extends('layouts.exam.app')

@section('title', $jadwal->nama_kegiatan . ' — Informasi Ujian')

@section('content')
<div class="page page-center min-vh-100">
    <div class="container-narrow py-4">
        <div class="card">

            {{-- Hero Header --}}
            <div class="card-body bg-primary text-white rounded-top">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary">
                        <i class="ti ti-school me-1"></i>Computer Based Test
                    </span>
                </div>
                <h2 class="text-white mb-1">{{ $jadwal->nama_kegiatan }}</h2>
                <p class="text-white-50 mb-0">
                    Selamat datang, <strong class="text-white">{{ auth()->user()->name }}</strong>.
                    Baca informasi ujian sebelum memulai.
                </p>
            </div>

            {{-- Stats --}}
            <div class="row row-0 g-0 border-bottom border-top">
                <div class="col text-center p-3 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                        <i class="ti ti-file-text text-primary"></i>
                        <span class="h2 mb-0 text-primary fw-bold">{{ $totalSoal }}</span>
                    </div>
                    <div class="text-muted small text-uppercase fw-semibold" style="letter-spacing:.5px">Total Soal</div>
                </div>
                <div class="col text-center p-3 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                        <i class="ti ti-clock text-primary"></i>
                        <span class="h2 mb-0 text-primary fw-bold">{{ $durasi }}</span>
                    </div>
                    <div class="text-muted small text-uppercase fw-semibold" style="letter-spacing:.5px">Menit</div>
                </div>
                <div class="col text-center p-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                        <i class="ti ti-calendar-time text-primary"></i>
                        <span class="h2 mb-0 text-primary fw-bold">{{ $jadwal->waktu_mulai->format('H:i') }}</span>
                    </div>
                    <div class="text-muted small text-uppercase fw-semibold" style="letter-spacing:.5px">Mulai</div>
                </div>
            </div>

            <div class="card-body">

                {{-- Detail Info --}}
                <div class="table-responsive mb-4">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-0" style="width:50%">
                                    <i class="ti ti-package me-2"></i>Paket Soal
                                </td>
                                <td class="fw-semibold">{{ $jadwal->paket->nama_paket }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">
                                    <i class="ti ti-clock-2 me-2"></i>Waktu Selesai
                                </td>
                                <td class="fw-semibold">{{ $jadwal->waktu_selesai->format('H:i') }} WIB</td>
                            </tr>
                            @if($jadwal->paket->is_acak_soal)
                            <tr>
                                <td class="text-muted ps-0">
                                    <i class="ti ti-arrows-shuffle me-2"></i>Urutan Soal
                                </td>
                                <td>
                                    <span class="badge bg-warning-lt text-warning">
                                        <i class="ti ti-shuffle me-1"></i>Diacak
                                    </span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Warning jika dilanjutkan --}}
                @if($existing && $existing->status === 'Sedang_Mengerjakan')
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ti ti-alert-triangle mt-1"></i>
                        <div>
                            <strong>Sesi Dilanjutkan</strong><br>
                            Anda sebelumnya sudah memulai ujian ini. Klik <strong>Lanjutkan Ujian</strong> untuk melanjutkan dari posisi terakhir.
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tata Tertib --}}
                <div class="card bg-light mb-4">
                    <div class="card-body py-3">
                        <h4 class="card-title text-uppercase small text-muted fw-bold" style="letter-spacing:.5px">
                            <i class="ti ti-info-circle me-1"></i>Tata Tertib Ujian
                        </h4>
                        <ul class="mb-0 ps-3">
                            <li class="mb-1 small">Pastikan koneksi internet Anda stabil selama ujian berlangsung.</li>
                            <li class="mb-1 small">Jawaban tersimpan otomatis setiap kali Anda memilih opsi.</li>
                            <li class="mb-1 small">Jangan pindah tab atau jendela browser saat ujian berlangsung.</li>
                            <li class="mb-1 small">Ujian akan berhenti otomatis ketika waktu habis.</li>
                            <li class="small">Klik <strong>Selesaikan Ujian</strong> setelah menjawab semua soal.</li>
                        </ul>
                    </div>
                </div>

                {{-- Tombol Mulai --}}
                <form action="{{ route('cbt.execute.start', $jadwal->hashid) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="ti ti-player-play me-2"></i>
                        {{ ($existing && $existing->status === 'Sedang_Mengerjakan') ? 'Lanjutkan Ujian' : 'Mulai Ujian Sekarang' }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
