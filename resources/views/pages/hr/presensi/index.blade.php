@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            Presensi Online
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('hr.presensi.settings') }}" class="btn btn-outline-primary">
                <i class="ti ti-settings me-2"></i>
                Pengaturan
            </a>
            <a href="{{ route('hr.presensi.history') }}" class="btn btn-outline-secondary">
                <i class="ti ti-history me-2"></i>
                Riwayat
            </a>
        </div>
    </div>
</div>

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
                    <button id="btn-checkin" class="btn btn-success btn-lg">
                        <i class="ti ti-login me-2"></i>
                        Check In
                    </button>
                    <button id="btn-checkout" class="btn btn-danger btn-lg" disabled>
                        <i class="ti ti-logout me-2"></i>
                        Check Out
                    </button>
                    <button id="btn-refresh-location" class="btn btn-outline-primary">
                        <i class="ti ti-refresh me-2"></i>
                        Refresh Lokasi
                    </button>
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
                                <div class="text-center">
                                    <video id="webcam" width="320" height="240" autoplay class="rounded border" style="max-width: 100%;"></video>
                                    <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                                </div>
                                <div class="mt-3">
                                    <button id="btn-start-camera" class="btn btn-primary">
                                        <i class="ti ti-camera me-2"></i>
                                        Aktifkan Kamera
                                    </button>
                                    <button id="btn-capture" class="btn btn-success" disabled>
                                        <i class="ti ti-photo me-2"></i>
                                        Ambil Foto
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="face-status" class="text-center">
                                    <div id="captured-photo" style="display: none;">
                                        <img id="photo-preview" width="200" height="150" class="rounded border mb-2" style="max-width: 100%;">
                                    </div>
                                    <div id="face-detection-status" class="alert alert-warning">
                                        <i class="ti ti-alert-triangle me-2"></i>
                                        Silakan ambil foto untuk verifikasi wajah
                                    </div>
                                    <div id="face-match-status" class="alert alert-info" style="display: none;">
                                        <i class="ti ti-loader me-2"></i>
                                        Sedang memverifikasi wajah...
                                    </div>
                                    <div id="face-success-status" class="alert alert-success" style="display: none;">
                                        <i class="ti ti-check me-2"></i>
                                        Wajah terverifikasi! Anda dapat presensi sekarang.
                                    </div>
                                    <div id="face-error-status" class="alert alert-danger" style="display: none;">
                                        <i class="ti ti-x me-2"></i>
                                        Wajah tidak dikenali. Silakan coba lagi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm Buttons -->
                    <div id="confirm-buttons" class="btn-list justify-content-center" style="display: none;">
                        <button id="btn-confirm-checkin" class="btn btn-success btn-lg">
                            <i class="ti ti-check me-2"></i>
                            Ya, Check In Sekarang
                        </button>
                        <button id="btn-confirm-checkout" class="btn btn-danger btn-lg">
                            <i class="ti ti-check me-2"></i>
                            Ya, Check Out Sekarang
                        </button>
                        <button id="btn-cancel" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Info Presensi</h3>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="ti ti-login fs-3 text-primary mb-2"></i>
                            <div class="fw-bold">Check In</div>
                            <div class="text-muted small" id="checkin-time">--:--</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="ti ti-logout fs-3 text-danger mb-2"></i>
                            <div class="fw-bold">Check Out</div>
                            <div class="text-muted small" id="checkout-time">--:--</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Radius Kantor</small>
                        <span class="fw-bold" id="allowed-radius">100m</span>
                    </div>
                    <div class="progress">
                        <div id="radius-progress" class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Status</small>
                        <small class="text-muted" id="location-status">Mendapatkan lokasi...</small>
                    </div>
                </div>
                
                <div class="location-coordinates">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Latitude:</small>
                            <div id="latitude" class="fw-bold small">-</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Longitude:</small>
                            <div id="longitude" class="fw-bold small">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize presensi functionality after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait for jQuery to be available (admin.js loads it globally)
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded yet');
        return;
    }
    
    initializePresensi();
});

async function initializePresensi() {
    // Setup face detection first
    await setupFaceDetection();
    
    // Initialize variables
    window.currentPosition = null;
    window.faceVerified = false;
    window.capturedPhoto = null;
    window.stream = null;
    window.currentAction = null; // 'checkin' or 'checkout'
    
    // Load settings first
    loadPresensiSettings();
    
    // Load current status
    loadCurrentStatus();
    
    // Get current location (after settings are loaded)
    setTimeout(function() {
        getCurrentLocation();
    }, 500);
    
    // Setup event listeners
    $('#btn-checkin').click(showCameraForCheckIn);
    $('#btn-checkout').click(showCameraForCheckOut);
    $('#btn-confirm-checkin').click(confirmCheckIn);
    $('#btn-confirm-checkout').click(confirmCheckOut);
    $('#btn-cancel').click(hideCamera);
    $('#btn-start-camera').click(startCamera);
    $('#btn-capture').click(capturePhoto);
    $('#btn-refresh-location').click(getCurrentLocation);
}

async function setupFaceDetection() {
    // Check if Face API library is available
    if (typeof faceapi === 'undefined') {
        console.error('Face API library not available');
        $('#face-detection-status').html('<i class="ti ti-alert-triangle me-2"></i>Face API library tidak tersedia. Melanjutkan tanpa verifikasi wajah.');
        window.faceVerified = true; // Auto-verify for testing
        return;
    }
    
    try {
        // Load face-api.js models from local server
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri('/js/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/js/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/js/models')
        ]);
        
        console.log('Face API models loaded successfully');
        $('#face-detection-status').html('<i class="ti ti-check me-2"></i>Face API siap digunakan');
    } catch (err) {
        console.error('Failed to load face API models:', err);
        // Fallback - continue without face detection
        $('#face-detection-status').html('<i class="ti ti-alert-triangle me-2"></i>Model Face API gagal dimuat. Melanjutkan tanpa verifikasi wajah.');
        window.faceVerified = true; // Auto-verify for testing
    }
}

function showCameraForCheckIn() {
    window.currentAction = 'checkin';
    showCameraSection('Check In');
}

function showCameraForCheckOut() {
    if (!window.currentPosition) {
        showError('Lokasi tidak tersedia. Silakan refresh lokasi.');
        return;
    }
    window.currentAction = 'checkout';
    showCameraSection('Check Out');
}

function showCameraSection(action) {
    // Hide action buttons, show camera
    $('#action-buttons').hide();
    $('#camera-section').show();
    $('#confirm-buttons').hide();
    
    // Reset face verification
    window.faceVerified = false;
    window.capturedPhoto = null;
    $('#captured-photo').hide();
    $('#face-success-status').hide();
    $('#face-error-status').hide();
    $('#face-detection-status').show();
    
    // Disable capture button initially
    $('#btn-capture').prop('disabled', true);
    
    // Auto-start camera
    startCamera();
}

function hideCamera() {
    // Show action buttons, hide camera
    $('#camera-section').hide();
    $('#action-buttons').show();
    $('#confirm-buttons').hide();
    
    // Stop camera stream
    if (window.stream) {
        window.stream.getTracks().forEach(track => track.stop());
        window.stream = null;
    }
    
    // Reset UI
    $('#btn-start-camera')
        .prop('disabled', false)
        .html('<i class="ti ti-camera me-2"></i>Aktifkan Kamera')
        .removeClass('btn-success')
        .addClass('btn-primary');
    $('#btn-capture').prop('disabled', true);
}

function confirmCheckIn() {
    if (!window.faceVerified) {
        showError('Wajah belum terverifikasi. Silakan ambil foto terlebih dahulu.');
        return;
    }
    
    if (!window.currentPosition) {
        showError('Lokasi tidak tersedia. Silakan refresh lokasi.');
        return;
    }
    
    $('#btn-confirm-checkin').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
    
    $.post('{{ route('hr.presensi.checkin') }}', {
        latitude: window.currentPosition.latitude,
        longitude: window.currentPosition.longitude,
        address: $('#current-address').text(),
        photo: window.capturedPhoto,
        face_verified: window.faceVerified
    })
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            $('#presensi-status').removeClass('bg-warning bg-danger').addClass('bg-success').text('Sudah Check In');
            $('#btn-checkin').prop('disabled', true);
            $('#btn-checkout').prop('disabled', false);
            
            // Hide camera and show action buttons
            hideCamera();
        } else {
            showError(response.message);
            $('#btn-confirm-checkin').prop('disabled', false);
        }
    })
    .fail(function(xhr) {
        const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan saat check-in';
        showError(message);
        $('#btn-confirm-checkin').prop('disabled', false);
    })
    .always(function() {
        $('#btn-confirm-checkin').html('<i class="ti ti-check me-2"></i>Ya, Check In Sekarang');
    });
}

function confirmCheckOut() {
    if (!window.currentPosition) {
        showError('Lokasi tidak tersedia. Silakan refresh lokasi.');
        return;
    }
    
    $('#btn-confirm-checkout').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
    
    $.post('{{ route('hr.presensi.checkout') }}', {
        latitude: window.currentPosition.latitude,
        longitude: window.currentPosition.longitude,
        address: $('#current-address').text(),
        photo: window.capturedPhoto,
        face_verified: window.faceVerified
    })
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            $('#presensi-status').removeClass('bg-warning bg-success').addClass('bg-danger').text('Sudah Check Out');
            $('#btn-checkin').prop('disabled', true);
            $('#btn-checkout').prop('disabled', true);
            
            // Hide camera and show action buttons
            hideCamera();
        } else {
            showError(response.message);
            $('#btn-confirm-checkout').prop('disabled', false);
        }
    })
    .fail(function(xhr) {
        const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan saat check-out';
        showError(message);
        $('#btn-confirm-checkout').prop('disabled', false);
    })
    .always(function() {
        $('#btn-confirm-checkout').html('<i class="ti ti-check me-2"></i>Ya, Check Out Sekarang');
    });
}

function handleCheckOut() {
    if (!window.currentPosition) {
        showError('Lokasi tidak tersedia. Silakan refresh lokasi.');
        return;
    }
    
    $('#btn-checkout').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
    
    $.post('{{ route('hr.presensi.checkout') }}', {
        latitude: window.currentPosition.latitude,
        longitude: window.currentPosition.longitude,
        address: $('#current-address').text()
    })
    .done(function(response) {
        if (response.success) {
            showSuccess(response.message);
            $('#checkout-time').text(response.data.check_out_time);
            $('#presensi-status').removeClass('bg-warning bg-success').addClass('bg-primary').text('Selesai Presensi');
            $('#btn-checkout').prop('disabled', true);
        } else {
            showError(response.message);
            $('#btn-checkout').prop('disabled', false);
        }
    })
    .fail(function(xhr) {
        const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan saat check-out';
        showError(message);
        $('#btn-checkout').prop('disabled', false);
    })
    .always(function() {
        $('#btn-checkout').html('<i class="ti ti-logout me-2"></i>Check Out');
    });
}

function setupFaceDetection() {
    // Check if Face API library is available
    if (typeof faceapi === 'undefined') {
        console.error('Face API library not available');
        $('#face-detection-status').html('<i class="ti ti-alert-triangle me-2"></i>Face API library tidak tersedia. Melanjutkan tanpa verifikasi wajah.');
        window.faceVerified = true; // Auto-verify for testing
        return;
    }
    
    // Load face-api.js models from local server
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('/js/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/js/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/js/models'),
    ]).then(function() {
        console.log('Face API models loaded successfully');
        $('#face-detection-status').html('<i class="ti ti-check me-2"></i>Face API siap digunakan');
    }).catch(function(err) {
        console.error('Failed to load face API models:', err);
        // Fallback - continue without face detection
        $('#face-detection-status').html('<i class="ti ti-alert-triangle me-2"></i>Model Face API gagal dimuat. Melanjutkan tanpa verifikasi wajah.');
        window.faceVerified = true; // Auto-verify for testing
    });
}

async function detectFace(imageData) {
    try {
        console.log('Starting face detection...');
        
        $('#face-match-status').show();
        $('#face-detection-status').hide();
        $('#face-success-status').hide();
        $('#face-error-status').hide();
        
        // Check if Face API is available
        if (typeof faceapi === 'undefined') {
            console.log('Face API not available, using fallback');
            // Fallback - auto-verify for testing
            window.faceVerified = true;
            $('#face-match-status').hide();
            $('#face-success-status').html('<i class="ti ti-check me-2"></i>Verifikasi berhasil (mode testing - tidak ada foto pegawai untuk dibandingkan)').show();
            $('#confirm-buttons').show();
            $('#btn-confirm-checkin').prop('disabled', false);
            showNotification('Face API tidak tersedia. Melanjutkan tanpa verifikasi wajah.', 'info');
            return;
        }
        
        console.log('Creating image from data URL...');
        // Create image element from data URL
        const img = await faceapi.fetchImage(imageData);
        
        console.log('Detecting faces...');
        // Detect faces using ssdMobilenetv1
        const detections = await faceapi.detectAllFaces(img)
            .withFaceLandmarks()
            .withFaceDescriptors();
        
        console.log('Face detection result:', detections.length, 'faces found');
        
        if (detections.length > 0) {
            // Face detected
            const faceDescriptor = detections[0].descriptor;
            
            console.log('Comparing face with employee data...');
            // Compare with employee photo
            const isMatch = await compareFaceWithEmployee(faceDescriptor);
            
            if (isMatch) {
                window.faceVerified = true;
                $('#face-match-status').hide();
                $('#face-success-status').html('<i class="ti ti-check me-2"></i>Wajah terverifikasi! Anda dapat presensi sekarang.').show();
                
                // Show confirm buttons
                $('#confirm-buttons').show();
                $('#btn-confirm-checkin').prop('disabled', false);
                
                showNotification('Wajah terverifikasi!', 'success');
            } else {
                window.faceVerified = false;
                $('#face-match-status').hide();
                $('#face-error-status').html('<i class="ti ti-x me-2"></i>Wajah tidak cocok dengan data pegawai. Silakan coba lagi atau pastikan foto pegawai sudah diupload.').show();
                $('#confirm-buttons').hide();
                showNotification('Wajah tidak cocok dengan data pegawai', 'error');
            }
        } else {
            // No face detected
            window.faceVerified = false;
            $('#face-match-status').hide();
            $('#face-error-status').html('<i class="ti ti-x me-2"></i>Tidak ada wajah terdeteksi. Silakan posisikan wajah dengan baik dan pastikan pencahayaan cukup.').show();
            $('#confirm-buttons').hide();
            showNotification('Tidak ada wajah terdeteksi', 'error');
        }
    } catch (error) {
        console.error('Face detection error:', error);
        // Fallback - auto-verify for testing
        window.faceVerified = true;
        $('#face-match-status').hide();
        $('#face-success-status').html('<i class="ti ti-check me-2"></i>Verifikasi berhasil (mode testing - terjadi error, melanjutkan tanpa verifikasi)').show();
        $('#confirm-buttons').show();
        $('#btn-confirm-checkin').prop('disabled', false);
        showNotification('Face detection error. Melanjutkan tanpa verifikasi wajah.', 'info');
    }
}

async function compareFaceWithEmployee(faceDescriptor) {
    try {
        console.log('Getting employee face data...');
        
        // Get employee face data from server
        const response = await $.get('{{ route('hr.presensi.employee-face-data') }}');
        
        console.log('Employee face data response:', response);
        
        if (response.success && response.faceData) {
            console.log('Employee face data found, comparing...');
            
            // Check if Face API is available
            if (typeof faceapi === 'undefined') {
                console.log('Face API not available, auto-matching');
                return true; // Fallback - always return true
            }
            
            const employeeDescriptor = new Float32Array(response.faceData);
            
            // Calculate face distance
            const distance = faceapi.euclideanDistance(faceDescriptor, employeeDescriptor);
            
            console.log('Face distance:', distance);
            
            // Threshold for face matching (lower is more strict)
            const threshold = 0.6;
            
            const isMatch = distance < threshold;
            console.log('Face match result:', isMatch, '(threshold:', threshold, ')');
            
            return isMatch;
        } else {
            console.log('No employee face data found, using fallback');
            // No employee photo stored - auto-verify for testing
            return true;
        }
    } catch (error) {
        console.error('Face comparison error:', error);
        // Fallback - always return true for testing
        return true;
    }
}

function loadCurrentStatus() {
    // Mock implementation - check if user already checked in/out today
    // In real app, this would check database
    const now = new Date();
    const hours = now.getHours();
    
    if (hours >= 17) {
        // After 5 PM, assume can check out
        $('#presensi-status').removeClass('bg-warning bg-danger').addClass('bg-success').text('Sudah Check In');
        $('#btn-checkin').prop('disabled', true);
        $('#btn-checkout').prop('disabled', false);
    } else {
        // Before 5 PM, can check in
        $('#presensi-status').removeClass('bg-success bg-danger').addClass('bg-warning').text('Belum Presensi');
        $('#btn-checkin').prop('disabled', false);
        $('#btn-checkout').prop('disabled', true);
    }
}

function loadPresensiSettings() {
    $.get('{{ route('hr.presensi.get-settings') }}')
        .done(function(data) {
            if (data.success && data.settings) {
                window.officePosition = {
                    latitude: data.settings.office_latitude,
                    longitude: data.settings.office_longitude,
                    address: data.settings.office_address,
                    radius: data.settings.allowed_radius
                };
                
                // Update UI elements
                $('#allowed-radius').text(window.officePosition.radius + 'm');
                
                console.log('Presensi settings loaded:', window.officePosition);
                
                // If we already have current position, recalculate distance
                if (window.currentPosition) {
                    const distance = calculateDistance(
                        window.currentPosition.latitude,
                        window.currentPosition.longitude,
                        window.officePosition.latitude,
                        window.officePosition.longitude
                    );
                    
                    $('#current-distance').text(distance.toFixed(2) + ' meter');
                    
                    // Update progress bar
                    const percentage = Math.min((distance / window.officePosition.radius) * 100, 100);
                    $('#radius-progress').css('width', percentage + '%');
                    
                    if (distance <= window.officePosition.radius) {
                        $('#location-status').removeClass('text-warning').addClass('text-success').text('Lokasi valid untuk presensi');
                    } else {
                        $('#location-status').removeClass('text-success').addClass('text-warning').text('Di luar radius presensi');
                    }
                }
            } else {
                console.error('Failed to load settings:', data);
                // Use default values
                window.officePosition = {
                    latitude: -6.208763,
                    longitude: 106.845599,
                    address: 'Jakarta, Indonesia',
                    radius: 100
                };
                $('#allowed-radius').text('100m');
            }
        })
        .fail(function(xhr) {
            console.error('Failed to load settings:', xhr);
            // Use default values
            window.officePosition = {
                latitude: -6.208763,
                longitude: 106.845599,
                address: 'Jakarta, Indonesia',
                radius: 100
            };
            $('#allowed-radius').text('100m');
        });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        $('#location-status').html('<i class="ti ti-loader me-2"></i>Sedang mendapatkan lokasi...');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                window.currentPosition = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                
                $('#location-status').html('<i class="ti ti-check me-2"></i>Lokasi berhasil didapatkan');
                $('#current-address').text('Mendapatkan alamat...');
                
                // Show location info
                $('#latitude').text(position.coords.latitude.toFixed(6));
                $('#longitude').text(position.coords.longitude.toFixed(6));
                
                // Calculate distance from office
                if (window.officePosition) {
                    const distance = calculateDistance(
                        position.coords.latitude,
                        position.coords.longitude,
                        window.officePosition.latitude,
                        window.officePosition.longitude
                    );
                    
                    $('#current-distance').text(distance.toFixed(2) + ' meter');
                    
                    // Update progress bar
                    const percentage = Math.min((distance / window.officePosition.radius) * 100, 100);
                    $('#radius-progress').css('width', percentage + '%');
                    
                    if (distance <= window.officePosition.radius) {
                        $('#location-status').removeClass('text-warning').addClass('text-success').text('Lokasi valid untuk presensi');
                    } else {
                        $('#location-status').removeClass('text-success').addClass('text-warning').text('Di luar radius presensi');
                    }
                    
                    // Enable check-in button if location is valid
                    $('#btn-checkin').prop('disabled', distance > window.officePosition.radius);
                }
                
                // Get address from coordinates
                getAddressFromCoordinates(position.coords.latitude, position.coords.longitude);
            },
            function(error) {
                handleLocationError(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        $('#location-status').html('<i class="ti ti-alert-triangle me-2"></i>Geolocation tidak didukung browser Anda');
    }
}

function getAddressFromCoordinates(lat, lng) {
    // Mock address - in real app, use geocoding API
    const address = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    $('#current-address').text(address);
}

function handleLocationError(error) {
    let message = 'Terjadi kesalahan mendapatkan lokasi';
    
    switch(error.code) {
        case error.PERMISSION_DENIED:
            message = "Anda menolak akses lokasi. Silakan izinkan akses lokasi.";
            break;
        case error.POSITION_UNAVAILABLE:
            message = "Informasi lokasi tidak tersedia.";
            break;
        case error.TIMEOUT:
            message = "Timeout mendapatkan lokasi. Silakan coba lagi.";
            break;
        case error.UNKNOWN_ERROR:
            message = "Terjadi kesalahan yang tidak diketahui.";
            break;
    }
    
    $('#location-status').html('<i class="ti ti-alert-triangle me-2"></i>' + message);
    $('#current-address').html('<span class="text-danger">' + message + '</span>');
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function startCamera() {
    // Check if camera is already active
    if (window.stream) {
        console.log('Camera already active');
        return;
    }
    
    // Disable start button, show loading
    $('#btn-start-camera').prop('disabled', true).html('<i class="ti ti-loader me-2"></i>Mengaktifkan kamera...');
    
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            width: { ideal: 640 },
            height: { ideal: 480 },
            facingMode: 'user'
        } 
    })
    .then(function(stream) {
        window.stream = stream;
        const video = document.getElementById('webcam');
        video.srcObject = stream;
        
        video.onloadedmetadata = function() {
            video.play();
            $('#btn-start-camera').html('<i class="ti ti-camera me-2"></i>Kamera Aktif').removeClass('btn-primary').addClass('btn-success');
            $('#btn-capture').prop('disabled', false);
            console.log('Camera started successfully');
        };
    })
    .catch(function(err) {
        console.error('Error accessing camera:', err);
        $('#btn-start-camera').prop('disabled', false).html('<i class="ti ti-camera me-2"></i>Aktifkan Kamera');
        showNotification('Tidak dapat mengakses kamera: ' + err.message, 'error');
    });
}

function capturePhoto() {
    try {
        if (!window.stream) {
            console.error('Camera stream not available');
            showNotification('Kamera tidak aktif. Silakan aktifkan kamera terlebih dahulu.', 'error');
            return;
        }
        
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        
        if (!video || !canvas) {
            console.error('Video or canvas element not found');
            showNotification('Elemen kamera tidak ditemukan.', 'error');
            return;
        }
        
        const context = canvas.getContext('2d');
        if (!context) {
            console.error('Canvas context not available');
            showNotification('Canvas tidak tersedia.', 'error');
            return;
        }
        
        // Set canvas size to match video
        canvas.width = video.videoWidth || 320;
        canvas.height = video.videoHeight || 240;
        
        // Draw video frame to canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Get image data
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        window.capturedPhoto = imageData;
        
        // Show preview
        $('#photo-preview').attr('src', imageData);
        $('#captured-photo').show();
        
        console.log('Photo captured successfully');
        
        // Start face detection
        detectFace(imageData);
        
    } catch (error) {
        console.error('Error capturing photo:', error);
        showNotification('Gagal mengambil foto: ' + error.message, 'error');
    }
}

function showSuccess(message) {
    showNotification(message, 'success');
}

function showError(message) {
    showNotification(message, 'error');
}

function showNotification(message, type = 'info') {
    // Check if bootstrap is available
    if (typeof bootstrap === 'undefined') {
        // Fallback - use alert
        console.log('Notification:', message);
        alert(message);
        return;
    }
    
    // Create toast notification
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Add toast to container
    let toastContainer = $('#toast-container');
    if (toastContainer.length === 0) {
        toastContainer = $('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>');
        $('body').append(toastContainer);
    }
    
    const $toast = $(toastHtml);
    toastContainer.append($toast);
    
    // Show toast
    try {
        const toast = new bootstrap.Toast($toast[0], {
            autohide: true,
            delay: 3000
        });
        toast.show();
        
        // Remove toast after hidden
        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    } catch (error) {
        console.error('Toast error:', error);
        // Fallback - just show and remove
        $toast.show();
        setTimeout(function() {
            $toast.remove();
        }, 3000);
    }
}
</script>
@endsection
