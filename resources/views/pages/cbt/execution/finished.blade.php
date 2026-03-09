@extends('layouts.assessment.app')

@section('title', 'Ujian Selesai')

@section('content')
<div class="page page-center min-vh-100 py-5">
    <div class="container-xl">
        


        <div class="row g-4">
            {{-- Left Column - Success Info --}}
            <div class="col-lg-7">
                {{-- Success Card --}}
                <x-tabler.card class="border-0 shadow-sm mb-2">
                    <x-tabler.card-body class="text-center p-5">
                        <div class="mb-4">
                            <div class="avatar avatar-xxl rounded-circle bg-success-lt shadow-sm mb-2" style="width: 100px; height: 100px; margin: 0 auto;">
                                <i class="ti ti-check" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        
                        <h2 class="display-6 fw-bold mb-2">Ujian Berhasil Diserahkan!</h2>

                        {{-- Exam Info Card --}}
                        <x-tabler.card class="bg-success-lt border-0 shadow-sm" style="border-radius: 1rem;">
                            <x-tabler.card-body class="py-4 px-3">
                                <h3 class="mb-1 fw-bold">{{ $jadwal->nama_kegiatan }}</h3>
                                <div class="text-muted small">
                                    <i class="ti ti-calendar-event me-1"></i>
                                    Selesai pada: {{ $riwayat->waktu_selesai ? $riwayat->waktu_selesai->format('d F Y, H:i') : now()->format('d F Y, H:i') }} WIB
                                </div>
                            </x-tabler.card-body>
                        </x-tabler.card>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>

            {{-- Right Column - Action Buttons --}}
            <div class="col-lg-5">
                {{-- Next Steps Card --}}
                <x-tabler.card class="border-0 shadow-sm mb-4">
                    <x-tabler.card-header class="bg-transparent border-0 pt-4 px-4" title="Langkah Selanjutnya" icon="ti ti-directions" titleClass="text-uppercase small text-muted fw-bold mb-0" />
                    <x-tabler.card-body class="px-4 pb-4">
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
                    </x-tabler.card-body>
                </x-tabler.card>

                {{-- Action Buttons --}}
                <x-tabler.card class="border-0 shadow-sm">
                    <x-tabler.card-body class="p-4">
                        <div class="d-grid gap-2">
                            <x-tabler.button href="{{ route('pmb.camaba.dashboard') }}" class="btn-primary btn-lg py-3" icon="ti ti-layout-dashboard" text="Kembali ke Dashboard" />
                            
                            @if(auth()->user()->hasRole('admin'))
                            <hr class="my-2">
                            <x-tabler.button href="{{ route('cbt.dashboard') }}" class="btn-outline-secondary btn-lg" icon="ti ti-settings" text="Admin Panel" />
                            <x-tabler.button href="{{ route('cbt.jadwal.index') }}" class="btn-outline-secondary btn-lg" icon="ti ti-calendar" text="Kelola Jadwal" />
                            @endif
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>

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
