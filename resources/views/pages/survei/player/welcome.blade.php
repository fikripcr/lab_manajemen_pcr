@extends('layouts.assessment.app')

@section('title', $survei->judul . ' — Informasi Survei')

@section('content')
<div class="page page-center min-vh-100 py-5">
    <div class="container-xl">
        
        {{-- Hero Header --}}
        <div class="assessment-hero text-white rounded-3 mb-4 p-4 p-md-5" style="background: linear-gradient(135deg, #2fb344 0%, #1e293b 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-white text-success fs-6 px-3 py-2">
                            <i class="ti ti-clipboard-list me-1"></i>Survei Online
                        </span>
                        @if($survei->wajib_login)
                        <span class="badge bg-azure-lt text-azure fs-6 px-3 py-2">
                            <i class="ti ti-lock me-1"></i>Login Required
                        </span>
                        @endif
                    </div>
                    <h1 class="display-5 fw-bold mb-2">{{ $survei->judul }}</h1>
                    @if($survei->deskripsi)
                    <p class="lead mb-0 opacity-75">{{ Str::limit($survei->deskripsi, 150) }}</p>
                    @endif
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <i class="ti ti-file-analytics" style="font-size: 8rem; opacity: 0.3;"></i>
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
                                    <i class="ti ti-question-mark text-success fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-success fw-bold">{{ $totalPertanyaan }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Pertanyaan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-clock text-success fs-1"></i>
                                </div>
                                <div class="h2 mb-0 text-success fw-bold">{{ $estimatedTime }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Menit (Est.)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ti ti-calendar text-success fs-1"></i>
                                </div>
                                <div class="h6 mb-0 text-success fw-bold">{{ $tanggalMulai ? $tanggalMulai->format('d M') : '-' }}</div>
                                <div class="text-muted small text-uppercase fw-semibold">Mulai</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Survey Details --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h3 class="card-title text-uppercase small text-muted fw-bold mb-0">
                            <i class="ti ti-info-circle me-2"></i>Detail Survei
                        </h3>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-borderless table-vcenter mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-calendar-event me-2"></i>Periode
                                        </td>
                                        <td class="fw-semibold py-3 text-end">
                                            {{ $tanggalMulai ? $tanggalMulai->format('d F Y') : '-' }} 
                                            <span class="text-muted">s/d</span> 
                                            {{ $tanggalSelesai ? $tanggalSelesai->format('d F Y') : 'Tidak terbatas' }}
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-user me-2"></i>Target Responden
                                        </td>
                                        <td class="fw-semibold py-3 text-end">
                                            @if($survei->target_role)
                                            <span class="badge bg-azure-lt text-azure px-3 py-2">{{ $survei->target_role }}</span>
                                            @else
                                            <span class="text-muted">Umum</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-3 ps-0">
                                            <i class="ti ti-repeat me-2"></i>Batas Pengisian
                                        </td>
                                        <td class="py-3 text-end">
                                            @if($survei->bisa_isi_ulang)
                                            <span class="badge bg-green-lt text-green px-3 py-2">
                                                <i class="ti ti-check me-1"></i>Boleh Diisi Ulang
                                            </span>
                                            @else
                                            <span class="badge bg-red-lt text-red px-3 py-2">
                                                <i class="ti ti-x me-1"></i>Sekali Saja
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Guidelines & Action --}}
            <div class="col-lg-5">
                {{-- Panduan Pengisian --}}
                <div class="card border-0 shadow-sm mb-4 bg-azure-lt">
                    <div class="card-body p-4">
                        <h4 class="card-title text-uppercase small text-azure fw-bold mb-3">
                            <i class="ti ti-info-circle me-2"></i>Panduan Pengisian
                        </h4>
                        <ul class="mb-0 ps-3 small">
                            <li class="mb-2">
                                <i class="ti ti-click me-2 text-azure"></i>
                                Klik pada <strong>kartu pertanyaan</strong> untuk membuka form jawaban.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-device-floppy me-2 text-azure"></i>
                                Jawaban <strong>tersimpan otomatis</strong> saat Anda mengisi.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-alert-circle me-2 text-azure"></i>
                                Pertanyaan dengan badge <span class="badge bg-red-lt ms-1">Wajib</span> harus diisi.
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-arrow-right me-2 text-azure"></i>
                                Gunakan tombol <strong>Lanjutkan</strong> untuk navigasi antar halaman.
                            </li>
                            <li>
                                <i class="ti ti-send me-2 text-azure"></i>
                                Klik <strong>Kirim Jawaban</strong> setelah semua pertanyaan terisi.
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('survei.public.start', $survei->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 mb-3">
                                <i class="ti ti-player-play me-2"></i>
                                Mulai Isi Survei
                            </button>
                        </form>
                        
                        <hr class="my-3">
                        <div class="text-center small text-muted">
                            <i class="ti ti-shield-lock me-1"></i>
                            Data Anda aman dan terjaga kerahasiaannya
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                @if(auth()->user() && auth()->user()->hasRole('admin'))
                <div class="mt-3 text-center">
                    <a href="{{ route('survei.builder', $survei->encrypted_survei_id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-edit me-1"></i>Edit Survei
                    </a>
                    <a href="{{ route('survei.responses', $survei->encrypted_survei_id) }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="ti ti-database me-1"></i>Lihat Responses
                    </a>
                </div>
                @endif
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
