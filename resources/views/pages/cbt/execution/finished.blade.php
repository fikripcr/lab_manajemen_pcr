@extends('layouts.tabler.app')
{{-- Use admin layout for consistency --}}

@section('title', 'Ujian Selesai')

@section('content')
<div class="d-flex flex-column justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <div class="mb-4">
                <div class="avatar avatar-xl rounded-circle bg-success-lt shadow-sm mb-3" style="width: 100px; height: 100px;">
                    <i class="ti ti-check ti-lg" style="font-size: 3rem;"></i>
                </div>
            </div>
            <h1 class="display-5 fw-bold mb-2">Terima Kasih!</h1>
            <p class="h2 text-muted mb-4">Ujian Anda telah berhasil diserahkan.</p>
            
            <div class="card bg-primary-lt border-0 shadow-sm mx-auto mb-4" style="max-width: 500px; border-radius: 16px;">
                <div class="card-body py-4">
                    <h3 class="mb-1">{{ $jadwal->nama_kegiatan }}</h3>
                    <div class="text-muted small">
                         Selesai pada: {{ $riwayat->waktu_selesai ? $riwayat->waktu_selesai->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <x-tabler.button href="{{ route('pmb.camaba.dashboard') }}" class="btn-primary btn-lg px-5 rounded-pill shadow-sm" icon="ti ti-layout-dashboard" text="Kembali ke Dashboard" />
                @if(auth()->user()->hasRole('admin'))
                    <x-tabler.button href="{{ route('cbt.dashboard') }}" class="btn-outline-secondary btn-lg px-5 rounded-pill" icon="ti ti-settings" text="Admin Panel" />
                @endif
            </div>
        </div>

<style>
    .avatar-xl i {
        color: var(--tblr-success);
    }
    body {
        background-color: #f8fafc;
    }
</style>
@endsection
