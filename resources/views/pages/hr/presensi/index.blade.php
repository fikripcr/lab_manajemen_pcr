@extends('layouts.tabler.app')

@section('title', 'Presensi Online')

@section('actions')
    <x-tabler.button href="{{ route('hr.presensi.settings') }}" class="btn-outline-primary" icon="ti ti-settings" text="Pengaturan" />
    <x-tabler.button href="{{ route('hr.presensi.history') }}" class="btn-outline-secondary" icon="ti ti-history" text="Riwayat" />
@endsection

@push('styles')
<!-- Face API CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.css">
@endpush

@push('scripts')
<!-- Face API JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
// Check if Face API loaded successfully
window.addEventListener('load', function() {
    if (typeof faceapi === 'undefined') {
        console.error('Face API library failed to load');
        // Fallback - set global flag
        window.faceApiLoaded = false;
    } else {
        console.log('Face API library loaded successfully');
        window.faceApiLoaded = true;
    }
});
</script>
@endpush

@section('content')
<div class="row row-deck row-cards">
    <!-- Main Status Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Presensi Hari Ini</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <div class="avatar avatar-xl rounded-circle" style="background-color: #f3f4f6;">
                            <i class="ti ti-user fs-2 text-muted"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="m-0">{{ auth()->user()->name }}</h4>
                        <p class="text-muted mb-2">{{ date('d F Y') }}</p>
                        <div class="status-info">
                            <span class="badge bg-warning text-white" id="presensi-status">Belum Presensi</span>
                        </div>
                    </div>
                </div>
                
                <!-- Location Info -->
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-map-pin fs-4 text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Lokasi Anda</small>
                                    <div id="current-address" class="fw-bold">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Mendapatkan lokasi...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-ruler fs-4 text-warning me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Jarak dari Kantor</small>
                                    <div id="current-distance" class="fw-bold">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div id="action-buttons" class="btn-list justify-content-center">
                    <x-tabler.button id="btn-checkin" class="btn-success btn-lg" icon="ti ti-login" text="Check In" />
                    <x-tabler.button id="btn-checkout" class="btn-danger btn-lg" disabled icon="ti ti-logout" text="Check Out" />
                    <x-tabler.button id="btn-refresh-location" class="btn-outline-primary" icon="ti ti-refresh" text="Refresh Lokasi" />
                </div>
                
                <!-- Webcam Section (Hidden by default) -->
                <div id="camera-section" style="display: none;">
                    <div class="border rounded p-4 bg-light mb-4">
                        <h5 class="mb-3">
                            <i class="ti ti-camera me-2"></i>
                            Verifikasi Wajah
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative border rounded bg-black" style="height: 300px;">
                                    <video id="webcam" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                    <canvas id="canvas" class="d-none"></canvas>
                                    <div id="face-boundary" class="position-absolute top-50 start-50 translate-middle border border-warning border-2" style="width: 200px; height: 200px; border-radius: 50%; display: none;"></div>
                                </div>
                                <div class="mt-2 text-center">
                                    <x-tabler.button id="btn-capture" class="btn-primary" icon="ti ti-camera" text="Ambil Foto" />
                                    <x-tabler.button id="btn-start-camera" class="btn-outline-info" icon="ti ti-video" text="Aktifkan Kamera" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="captured-photo" class="text-center" style="display: none;">
                                    <div class="border rounded bg-light" style="height: 300px;">
                                        <img id="photo-preview" src="" style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-success" id="photo-status">Foto Berhasil Diambil</span>
                                    </div>
                                </div>
                                <div id="face-detection-status" class="alert alert-info mt-3" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm me-3"></div>
                                        <span>Sedang memproses verifikasi wajah...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <x-tabler.button id="btn-submit-presensi" class="btn-success btn-lg px-5" icon="ti ti-check" text="Kirim Presensi" disabled />
                        <x-tabler.button id="btn-cancel-camera" class="btn-link text-muted" text="Batal" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Info Card -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Informasi Jabatan</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small uppercase">Jabatan Utama</label>
                    <div class="fw-bold">{{ auth()->user()->pegawai->jabatan_terakhir ?? 'Staf' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small uppercase">Unit Kerja</label>
                    <div class="fw-bold">{{ auth()->user()->pegawai->unit_kerja ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label text-muted small uppercase">Status Kepegawaian</label>
                    <div class="fw-bold">
                        <span class="badge bg-blue-lt">Tetap</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jadwal Kerja</h3>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col">Senin - Kamis</div>
                            <div class="col-auto fw-bold">07:30 - 16:30</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col">Jumat</div>
                            <div class="col-auto fw-bold">07:30 - 16:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mock script for testing headers
document.addEventListener('DOMContentLoaded', function() {
    console.log('Presensi page loaded');
    
    $('#btn-checkin').on('click', function() {
        $('#action-buttons').hide();
        $('#camera-section').show();
        startCamera();
    });
    
    $('#btn-cancel-camera').on('click', function() {
        $('#camera-section').hide();
        $('#action-buttons').show();
        stopCamera();
    });
    
    // Simulate location gathering
    setTimeout(function() {
        $('#current-address').html('Kampus Politeknik Caltex Riau, Pekanbaru');
        $('#current-distance').html('150 meter <span class="badge bg-success ms-2">Dalam Radius</span>');
    }, 1500);
});

function startCamera() {
    console.log('Starting camera mock...');
    $('#btn-start-camera').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghubungkan...');
    
    // Mock success after 1s
    setTimeout(function() {
        $('#btn-start-camera').html('<i class="ti ti-camera me-2"></i>Kamera Aktif').removeClass('btn-primary').addClass('btn-success');
    }, 1000);
}

function stopCamera() {
    console.log('Stopping camera mock');
}
</script>
@endsection
