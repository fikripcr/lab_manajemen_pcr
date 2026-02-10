@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            <i class="ti ti-settings me-2"></i>
            Pengaturan Presensi
        </h2>
        <div class="text-muted mt-1">Konfigurasi lokasi kantor dan radius presensi online</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('hr.presensi.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Main Settings Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar avatar-lg bg-primary-lt">
                            <i class="ti ti-map-pin fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="card-title mb-1">Pengaturan Lokasi & Radius</h3>
                        <div class="text-muted">Tentukan lokasi kantor dan batas radius presensi</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="settings-form" action="{{ route('hr.presensi.update-settings') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <!-- Location Coordinates -->
                        <div class="col-12">
                            <div class="border rounded p-4 bg-light">
                                <h5 class="mb-3">
                                    <i class="ti ti-gps me-2"></i>
                                    Koordinat Lokasi Kantor
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="ti ti-arrow-north me-1"></i>
                                            Latitude
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <span class="input-group-text">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <input type="number" step="0.000001" name="office_latitude" id="office_latitude" 
                                                   class="form-control" value="-6.208763" required
                                                   placeholder="Contoh: -6.208763">
                                            <button type="button" class="btn btn-primary" id="btn-get-lat" title="Dapatkan lokasi saat ini">
                                                <i class="ti ti-crosshair"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Koordinat latitude lokasi kantor (contoh: -6.208763)</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="ti ti-arrow-east me-1"></i>
                                            Longitude
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <span class="input-group-text">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <input type="number" step="0.000001" name="office_longitude" id="office_longitude" 
                                                   class="form-control" value="106.845599" required
                                                   placeholder="Contoh: 106.845599">
                                            <button type="button" class="btn btn-primary" id="btn-get-lng" title="Dapatkan lokasi saat ini">
                                                <i class="ti ti-crosshair"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Koordinat longitude lokasi kantor (contoh: 106.845599)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="ti ti-home me-2"></i>
                                Alamat Kantor
                            </label>
                            <textarea name="office_address" id="office_address" rows="3" 
                                      class="form-control" required
                                      placeholder="Masukkan alamat lengkap kantor...">Jakarta, Indonesia</textarea>
                            <div class="form-text">Alamat lengkap kantor yang akan ditampilkan di presensi</div>
                        </div>

                        <!-- Radius Settings -->
                        <div class="col-12">
                            <div class="border rounded p-4 bg-light">
                                <h5 class="mb-3">
                                    <i class="ti ti-ruler me-2"></i>
                                    Pengaturan Radius
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">
                                            <i class="ti me-1"></i>
                                            Radius Presensi
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <span class="input-group-text">
                                                <i class="ti ti-ruler-2"></i>
                                            </span>
                                            <input type="range" name="allowed_radius" id="allowed_radius" 
                                                   class="form-range" min="10" max="1000" value="100" step="10">
                                            <span class="input-group-text">
                                                <span id="radius-value">100</span> m
                                            </span>
                                        </div>
                                        <div class="form-text">
                                            Radius maksimum untuk presensi (10-1000 meter)
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-white rounded border">
                                            <div class="text-muted small mb-1">Radius Aktif</div>
                                            <div class="h2 mb-0 text-primary">
                                                <i class="ti ti-ruler-2"></i>
                                                <span id="radius-display">100</span>
                                                <small class="text-muted">m</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <div class="col-12">
                            <div class="form-label fw-semibold">
                                <i class="ti me-2"></i>
                                Status Presensi
                            </div>
                            <div class="form-check form-switch form-check-lg">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <span class="form-switch-label">
                                        <i class="ti ti-power me-2"></i>
                                        Aktifkan Presensi Online
                                    </span>
                                </label>
                            </div>
                            <div class="form-text">Aktifkan fitur presensi online untuk karyawan</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-danger" id="btn-reset-default">
                                    <i class="ti ti-refresh me-2"></i>
                                    Reset Default
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-2"></i>
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Test Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar avatar-lg bg-success-lt">
                            <i class="ti ti-crosshair fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="card-title mb-1">Test Lokasi</h3>
                        <div class="text-muted">Uji lokasi Anda secara real-time</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Test Status -->
                <div id="test-status" class="alert alert-info d-none">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-3"></div>
                        <div>
                            <strong>Mendapatkan lokasi Anda...</strong>
                            <div class="small">Mohon tunggu sebentar</div>
                        </div>
                    </div>
                </div>
                
                <!-- Test Results -->
                <div id="test-results" style="display: none;">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Lokasi Anda Saat Ini</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-map-pin text-primary me-2"></i>
                                <span class="fw-semibold">Koordinat:</span>
                            </div>
                            <div id="test-current-location" class="font-monospace text-muted"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Lokasi Kantor</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-building text-success me-2"></i>
                                <span class="fw-semibold">Koordinat:</span>
                            </div>
                            <div id="test-office-location" class="font-monospace text-muted"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Jarak & Status</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Jarak:</span>
                                <span id="test-distance" class="h5 mb-0 text-primary"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Status:</span>
                                <span id="test-status-result"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Test Button -->
                <button type="button" class="btn btn-success w-100 btn-lg" id="btn-test-location">
                    <i class="ti ti-crosshair me-2"></i>
                    Test Lokasi Sekarang
                </button>
                <p class="text-muted text-center mt-2 mb-0">
                    <small>Klik untuk menguji apakah lokasi Anda berada dalam radius presensi</small>
                </p>
            </div>
        </div>

        <!-- Quick Info Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="ti ti-info-circle me-2"></i>
                    Informasi Penting
                </h5>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="avatar avatar-sm bg-blue-lt">
                                <i class="ti ti-map-pin"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold">Lokasi Akurat</div>
                            <div class="text-muted small">Pastikan koordinat kantor akurat untuk validasi presensi yang tepat</div>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="avatar avatar-sm bg-green-lt">
                                <i class="ti ti-ruler-2"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold">Radius Sesuai</div>
                            <div class="text-muted small">Atur radius sesuai kebutuhan area kantor Anda</div>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="avatar avatar-sm bg-orange-lt">
                                <i class="ti ti-test-pipe"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold">Test Lokasi</div>
                            <div class="text-muted small">Selalu test lokasi setelah mengubah pengaturan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Test Modal (Hidden, using inline instead) -->
<div class="modal fade" id="locationTestModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Lokasi Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="test-status" class="alert alert-info">
                    <i class="ti ti-map-pin me-2"></i>
                    Mendapatkan lokasi Anda...
                </div>
                
                <div id="test-results" style="display: none;">
                    <div class="mb-3">
                        <h6>Lokasi Anda:</h6>
                        <div id="test-current-location" class="text-muted"></div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Lokasi Kantor:</h6>
                        <div id="test-office-location" class="text-muted"></div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Jarak:</h6>
                        <div id="test-distance" class="fw-bold"></div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Status:</h6>
                        <div id="test-status-result"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-retest">Test Ulang</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded yet');
        return;
    }
    
    initializeSettings();
});

function initializeSettings() {
    loadCurrentSettings();
    updateRadiusDisplay();
    
    // Event listeners
    $('#settings-form').submit(handleSaveSettings);
    $('#btn-get-lat, #btn-get-lng').click(getCurrentLocationForField);
    $('#btn-test-location').click(testLocation);
    $('#btn-reset-default').click(resetToDefault);
    
    // Range slider real-time update
    $('#allowed_radius').on('input', function() {
        updateRadiusDisplay();
    });
    
    // Update preview on input change
    $('#office_latitude, #office_longitude').on('input', updateOfficeLocationDisplay);
}

function loadCurrentSettings() {
    console.log('Loading current settings...');
    $.get('{{ route('hr.presensi.get-settings') }}')
        .done(function(data) {
            console.log('Settings loaded:', data);
            if (data.success && data.settings) {
                $('#office_latitude').val(data.settings.office_latitude);
                $('#office_longitude').val(data.settings.office_longitude);
                $('#office_address').val(data.settings.office_address);
                $('#allowed_radius').val(data.settings.allowed_radius);
                $('#is_active').prop('checked', data.settings.is_active);
                
                updateRadiusDisplay();
                updateOfficeLocationDisplay();
                
                console.log('Settings applied to form');
            } else {
                console.warn('No settings found in response');
            }
        })
        .fail(function(xhr) {
            console.error('Failed to load settings:', xhr.responseText);
        });
}

function updateRadiusDisplay() {
    const radius = $('#allowed_radius').val();
    $('#radius-value').text(radius);
    $('#radius-display').text(radius);
}

function updateOfficeLocationDisplay() {
    const lat = $('#office_latitude').val();
    const lng = $('#office_longitude').val();
    
    // Update test office location display
    if (lat && lng) {
        $('#test-office-location').text(`${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`);
    }
}

function getCurrentLocationForField(e) {
    const targetField = $(e.target).attr('id') === 'btn-get-lat' ? '#office_latitude' : '#office_longitude';
    
    const $button = $(e.target);
    const originalContent = $button.html();
    $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const value = position.coords.latitude.toFixed(6);
                if (targetField === '#office_latitude') {
                    $(targetField).val(position.coords.latitude.toFixed(6));
                } else {
                    $(targetField).val(position.coords.longitude.toFixed(6));
                }
                
                updateOfficeLocationDisplay();
                getAddressFromCoordinates(position.coords.latitude, position.coords.longitude);
                
                $button.prop('disabled', false).html(originalContent);
                
                // Show success feedback
                showNotification('Lokasi berhasil didapatkan!', 'success');
            },
            function(error) {
                let message = 'Gagal mendapatkan lokasi';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Akses lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Informasi lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        message = 'Timeout mendapatkan lokasi';
                        break;
                }
                
                showNotification(message, 'error');
                $button.prop('disabled', false).html(originalContent);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        showNotification('Geolocation tidak didukung browser Anda', 'error');
        $button.prop('disabled', false).html(originalContent);
    }
}

function getAddressFromCoordinates(lat, lng) {
    // Mock address for now - in real app, this would call geocoding API
    const mockAddress = `Lat: ${lat}, Lng: ${lng}`;
    $('#office_address').val(mockAddress);
}

function handleSaveSettings(e) {
    e.preventDefault();
    
    const $submitBtn = $('#settings-form button[type="submit"]');
    const originalContent = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
    
    const formData = {
        office_latitude: parseFloat($('#office_latitude').val()) || 0,
        office_longitude: parseFloat($('#office_longitude').val()) || 0,
        office_address: $('#office_address').val() || '',
        allowed_radius: parseInt($('#allowed_radius').val()) || 100,
        is_active: $('#is_active').is(':checked')
    };
    
    console.log('Saving settings:', formData);
    
    // Validate client-side first
    if (!formData.office_latitude || !formData.office_longitude) {
        showNotification('Latitude dan Longitude harus diisi', 'error');
        $submitBtn.prop('disabled', false).html(originalContent);
        return;
    }
    
    if (!formData.office_address.trim()) {
        showNotification('Alamat kantor harus diisi', 'error');
        $submitBtn.prop('disabled', false).html(originalContent);
        return;
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Debug CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
    console.log('Form action:', $('#settings-form').attr('action'));
    
    // Use form action if available, otherwise use route
    const postUrl = '/hr/presensi/update-settings'; // Hardcoded for testing
    console.log('Posting to:', postUrl);
    
    $.ajax({
        url: postUrl,
        method: 'POST',
        data: formData,
        success: function(response) {
            console.log('Save response:', response);
            if (response.success) {
                showNotification(response.message, 'success');
                updateOfficeLocationDisplay();
                
                // Reload settings to confirm they were saved
                setTimeout(() => {
                    console.log('Reloading settings to verify...');
                    loadCurrentSettings();
                }, 500);
            } else {
                // Handle validation errors
                if (response.errors) {
                    let errorMessage = 'Validation error:<br>';
                    for (let field in response.errors) {
                        errorMessage += `• ${response.errors[field][0]}<br>`;
                    }
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification(response.message || 'Terjadi kesalahan', 'error');
                }
            }
        },
        error: function(xhr) {
            console.error('Save failed:', xhr);
            console.error('Status:', xhr.status);
            console.error('ResponseText:', xhr.responseText);
            
            let message = 'Terjadi kesalahan';
            
            if (xhr.status === 419) {
                message = 'CSRF token mismatch. Silakan refresh halaman.';
            } else if (xhr.status === 405) {
                message = 'Method not allowed. URL: ' + postUrl;
            } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                message = 'Validation error:<br>';
                for (let field in xhr.responseJSON.errors) {
                    message += `• ${xhr.responseJSON.errors[field][0]}<br>`;
                }
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            showNotification(message, 'error');
        },
        complete: function() {
            $submitBtn.prop('disabled', false).html(originalContent);
        }
    });
}

function testLocation() {
    const $testBtn = $('#btn-test-location');
    const originalContent = $testBtn.html();
    $testBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Testing...');
    
    // Show loading state
    $('#test-results').hide();
    $('#test-status').removeClass('d-none alert-danger alert-success').addClass('alert-info').html(`
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-3"></div>
            <div>
                <strong>Mendapatkan lokasi Anda...</strong>
                <div class="small">Mohon tunggu sebentar</div>
            </div>
        </div>
    `).show();
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;
                // Debug form values before calculation
                console.log('Form values before calculation:');
                console.log('Office Latitude field value:', $('#office_latitude').val());
                console.log('Office Longitude field value:', $('#office_longitude').val());
                console.log('Allowed Radius field value:', $('#allowed_radius').val());
                
                const officeLat = parseFloat($('#office_latitude').val());
                const officeLng = parseFloat($('#office_longitude').val());
                const allowedRadius = parseInt($('#allowed_radius').val());
                
                console.log('Parsed values:', {officeLat, officeLng, allowedRadius});
                
                // Validate coordinates
                if (isNaN(officeLat) || isNaN(officeLng)) {
                    $('#test-status').removeClass('alert-info alert-success').addClass('alert-danger').html(`
                        <div class="d-flex align-items-center">
                            <i class="ti ti-alert-triangle me-3"></i>
                            <div>
                                <strong>Koordinat kantor tidak valid</strong>
                                <div class="small">Silakan periksa pengaturan lokasi kantor</div>
                            </div>
                        </div>
                    `);
                    $testBtn.prop('disabled', false).html(originalContent);
                    return;
                }
                
                console.log('Test coordinates:', {
                    current: {lat: currentLat, lng: currentLng},
                    office: {lat: officeLat, lng: officeLng},
                    radius: allowedRadius
                });
                
                const distance = calculateDistance(currentLat, currentLng, officeLat, officeLng);
                
                // Debug distance calculation
                console.log('Distance calculation:');
                console.log('Current Location:', {lat: currentLat, lng: currentLng});
                console.log('Office Location:', {lat: officeLat, lng: officeLng});
                console.log('Calculated Distance:', distance + ' meters');
                
                const isValid = distance <= allowedRadius;
                
                // Update results
                $('#test-current-location').text(`${currentLat.toFixed(6)}, ${currentLng.toFixed(6)}`);
                $('#test-office-location').text(`${officeLat.toFixed(6)}, ${officeLng.toFixed(6)}`);
                $('#test-distance').text(`${distance.toFixed(2)} meter`);
                
                if (isValid) {
                    $('#test-status-result').html('<span class="badge bg-success"><i class="ti ti-check me-1"></i>Valid untuk presensi</span>');
                } else {
                    $('#test-status-result').html('<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Di luar radius presensi</span>');
                }
                
                // Hide loading, show results
                $('#test-status').addClass('d-none');
                $('#test-results').show();
                
                // Visual feedback
                if (isValid) {
                    $('#test-distance').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#test-distance').removeClass('text-success').addClass('text-danger');
                }
                
                $testBtn.prop('disabled', false).html(originalContent);
            },
            function(error) {
                let message = 'Gagal mendapatkan lokasi';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Akses lokasi ditolak. Silakan izinkan akses lokasi.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Informasi lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        message = 'Timeout mendapatkan lokasi';
                        break;
                }
                
                $('#test-status').removeClass('alert-info alert-success').addClass('alert-danger').html(`
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle me-3"></i>
                        <div>
                            <strong>${message}</strong>
                            <div class="small">Silakan coba lagi</div>
                        </div>
                    </div>
                `);
                
                $testBtn.prop('disabled', false).html(originalContent);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        $('#test-status').removeClass('alert-info alert-success').addClass('alert-danger').html(`
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-triangle me-3"></i>
                <div>
                    <strong>Geolocation tidak didukung</strong>
                    <div class="small">Browser Anda tidak mendukung geolocation</div>
                </div>
            </div>
        `);
        
        $testBtn.prop('disabled', false).html(originalContent);
    }
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    console.log('calculateDistance inputs:', {lat1, lon1, lat2, lon2});
    
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    
    console.log('Differences:', {dLat, dLon});
    
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    
    console.log('Haversine a:', a);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    console.log('Haversine c:', c);
    
    const distance = R * c;
    console.log('Final distance:', distance);
    
    return distance;
}

function resetToDefault() {
    Swal.fire({
        title: 'Reset Pengaturan?',
        html: `
            <div class="text-start">
                <p>Apakah Anda yakin ingin mereset pengaturan ke nilai default?</p>
                <div class="mt-3">
                    <strong>Default Settings:</strong>
                    <ul class="mt-2">
                        <li>Latitude: -6.208763</li>
                        <li>Longitude: 106.845599</li>
                        <li>Radius: 100 meter</li>
                        <li>Status: Aktif</li>
                    </ul>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#office_latitude').val(-6.208763);
            $('#office_longitude').val(106.845599);
            $('#office_address').val('Jakarta, Indonesia');
            $('#allowed_radius').val(100);
            $('#is_active').prop('checked', true);
            
            updateRadiusDisplay();
            updateOfficeLocationDisplay();
            
            showNotification('Pengaturan telah direset ke nilai default', 'success');
        }
    });
}

function showNotification(message, type = 'info') {
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
    const toast = new bootstrap.Toast($toast[0], {
        autohide: true,
        delay: 5000 // Longer for validation errors
    });
    toast.show();
    
    // Remove toast after hidden
    $toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>
@endsection
