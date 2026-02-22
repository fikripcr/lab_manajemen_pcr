@extends('layouts.assessment.app')

@section('title', 'Ujian Selesai')

@section('content')
<div class="page page-center min-vh-100 py-5">
    <div class="container-xl">
        


        <div class="row g-4">
            {{-- Left Column - Success Info --}}
            <div class="col-lg-7">
                {{-- Success Card --}}
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <div class="avatar avatar-xxl rounded-circle bg-success-lt shadow-sm mb-2" style="width: 100px; height: 100px; margin: 0 auto;">
                                <i class="ti ti-check" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        
                        <h2 class="display-6 fw-bold mb-2">Ujian Berhasil Diserahkan!</h2>

                        {{-- Exam Info Card --}}
                        <div class="card bg-success-lt border-0 shadow-sm " style="border-radius: 1rem;">
                            <div class="card-body py-4 px-3">
                                <h3 class="mb-1 fw-bold">{{ $jadwal->nama_kegiatan }}</h3>
                                <div class="text-muted small">
                                    <i class="ti ti-calendar-event me-1"></i>
                                    Selesai pada: {{ $riwayat->waktu_selesai ? $riwayat->waktu_selesai->format('d F Y, H:i') : now()->format('d F Y, H:i') }} WIB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                {{-- <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-clock text-success fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-success fw-bold">{{ $riwayat->waktu_selesai ? $riwayat->waktu_selesai->diffInMinutes($riwayat->waktu_mulai) : '-' }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Waktu Pengerjaan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-file-text text-success fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-success fw-bold">{{ $riwayat->jawaban->where('is_correct', true)->count() ?? '-' }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Jawaban Benar</div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            {{-- Right Column - Action Buttons --}}
            <div class="col-lg-5">
                {{-- Next Steps Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h3 class="card-title text-uppercase small text-muted fw-bold mb-0">
                            <i class="ti ti-directions me-2"></i>Langkah Selanjutnya
                        </h3>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <ul class="mb-0 ps-3 small">
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                Tunggu pengumuman hasil ujian dari panitia.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                Hasil akan diumumkan melalui dashboard Anda.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                Pastikan kontak Anda tetap aktif untuk informasi lebih lanjut.
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <x-tabler.button href="{{ route('pmb.camaba.dashboard') }}" class="btn-primary btn-lg py-3" icon="ti ti-layout-dashboard" text="Kembali ke Dashboard" />
                            
                            @if(auth()->user()->hasRole('admin'))
                            <hr class="my-2">
                            <x-tabler.button href="{{ route('cbt.dashboard') }}" class="btn-outline-secondary btn-lg" icon="ti ti-settings" text="Admin Panel" />
                            <x-tabler.button href="{{ route('cbt.jadwal.index') }}" class="btn-outline-secondary btn-lg" icon="ti ti-calendar" text="Kelola Jadwal" />
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="ti ti-info-circle me-1"></i>
                        Jika ada pertanyaan, hubungi panitia ujian.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-xxl i {
        color: var(--tblr-success);
    }
</style>
@endsection
