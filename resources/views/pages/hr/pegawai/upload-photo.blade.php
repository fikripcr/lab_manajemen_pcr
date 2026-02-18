@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            Upload Foto Pegawai
        </h2>
    </div>
</div>

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Foto untuk Face Recognition</h3>
            </div>
            <div class="card-body">
                <form id="uploadPhotoForm" enctype="multipart/form-data">
                    @csrf
                    <x-tabler.form-input type="file" id="photo" name="photo" label="Foto Pegawai" accept="image/*" required="true" help="Upload foto yang jelas wajahnya untuk face recognition yang akurat." />
                    
                    <div class="mb-3">
                        <x-tabler.form-checkbox 
                            id="extractFace" 
                            name="extract_face" 
                            label="Extract face encoding otomatis" 
                            checked 
                            description="Centang untuk otomatis extract face encoding dari foto." 
                        />
                    </div>
                    
                    <div class="mb-3" id="webcamSection" style="display: none;">
                        <label class="form-label">Atau Ambil Foto dari Webcam</label>
                        <div class="text-center mb-3">
                            <video id="webcam" width="320" height="240" autoplay class="rounded border" style="max-width: 100%;"></video>
                            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                        </div>
                        <div class="text-center">
                            <x-tabler.button type="button" id="btnStartCamera" class="btn-primary" icon="ti ti-camera" text="Aktifkan Kamera" />
                            <x-tabler.button type="button" id="btnCapturePhoto" class="btn-success" icon="ti ti-photo" text="Ambil Foto" disabled />
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <x-tabler.button type="button" id="btnToggleWebcam" class="btn-outline-secondary" icon="ti ti-camera" text="Gunakan Webcam" />
                    </div>
                    
                    <div class="mb-3">
                        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-upload" text="Upload Foto" />
                        <x-tabler.button href="{{ route('hr.pegawai.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Preview</h5>
            </div>
            <div class="card-body">
                <div id="photoPreview" class="text-center">
                    <div class="avatar avatar-xl rounded-circle" style="background-color: #f3f4f6;">
                        <i class="ti ti-user fs-2 text-muted"></i>
                    </div>
                    <p class="text-muted mt-2">Belum ada foto</p>
                </div>
                
                <div id="faceStatus" class="mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <i class="ti ti-loader me-2"></i>
                        <span id="faceStatusText">Mendeteksi wajah...</span>
                    </div>
                </div>
                
                <div id="encodingInfo" class="mt-3" style="display: none;">
                    <h6>Face Encoding Info:</h6>
                    <small class="text-muted">
                        <div>Dimensions: <span id="encodingDimensions">-</span></div>
                        <div>First 5 values: <span id="encodingSample">-</span></div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
let stream = null;
let faceEncoding = null;

$(document).ready(function() {
    setupFormHandlers();
    setupWebcam();
    setupFaceDetection();
});

function setupFormHandlers() {
    // Wait for FilePond to initialize
    const checkFilePond = setInterval(() => {
        if (window.FilePond) {
            const pond = window.FilePond.find(document.querySelector('#photo'));
            if (pond) {
                clearInterval(checkFilePond);
                pond.on('addfile', (error, file) => {
                    if (error) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photoPreview').html(`
                            <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                            <p class="text-muted mt-2">${file.filename}</p>
                        `);
                        
                        if ($('#extractFace').is(':checked')) {
                            extractFaceEncoding(e.target.result);
                        }
                    };
                    reader.readAsDataURL(file.file);
                });

                pond.on('removefile', () => {
                   $('#photoPreview').html(`
                        <div class="avatar avatar-xl rounded-circle" style="background-color: #f3f4f6;">
                            <i class="ti ti-user fs-2 text-muted"></i>
                        </div>
                        <p class="text-muted mt-2">Belum ada foto</p>
                    `);
                   faceEncoding = null;
                   $('#encodingInfo').hide();
                   $('#faceStatus').hide();
                });
            }
        }
    }, 100);
    
    $('#btnToggleWebcam').click(function() {
        $('#webcamSection').slideToggle();
        $(this).toggleClass('btn-outline-secondary btn-outline-primary');
    });
    
    $('#uploadPhotoForm').submit(function(e) {
        e.preventDefault();
        uploadPhoto();
    });
}

function setupWebcam() {
    $('#btnStartCamera').click(function() {
        startWebcam();
    });
    
    $('#btnCapturePhoto').click(function() {
        captureFromWebcam();
    });
}

async function startWebcam() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            } 
        });
        
        const video = document.getElementById('webcam');
        video.srcObject = stream;
        
        video.onloadedmetadata = function() {
            video.play();
            $('#btnStartCamera').prop('disabled', true).text('Kamera Aktif');
            $('#btnCapturePhoto').prop('disabled', false);
        };
    } catch (error) {
        console.error('Error accessing camera:', error);
        alert('Tidak dapat mengakses kamera: ' + error.message);
    }
}

function captureFromWebcam() {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    
    context.drawImage(video, 0, 0, 320, 240);
    const imageData = canvas.toDataURL('image/jpeg');
    
    $('#photoPreview').html(`
        <img src="${imageData}" class="img-fluid rounded" style="max-height: 200px;">
        <p class="text-muted mt-2">Foto dari webcam</p>
    `);
    
    if ($('#extractFace').is(':checked')) {
        extractFaceEncoding(imageData);
    }
}

async function setupFaceDetection() {
    try {
        await faceapi.nets.tinyFaceDetector.loadFromUri('/js/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/js/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/js/models');
        console.log('Face API models loaded');
    } catch (error) {
        console.error('Failed to load Face API models:', error);
    }
}

async function extractFaceEncoding(imageData) {
    try {
        $('#faceStatus').show();
        $('#faceStatusText').text('Mendeteksi wajah...');
        
        const img = await faceapi.fetchImage(imageData);
        const detections = await faceapi.detectAllFaces(img)
            .withFaceLandmarks()
            .withFaceDescriptors();
        
        if (detections.length > 0) {
            const descriptor = detections[0].descriptor;
            faceEncoding = Array.from(descriptor);
            
            $('#faceStatus').html(`
                <div class="alert alert-success">
                    <i class="ti ti-check me-2"></i>
                    <span>Wajah terdeteksi! Encoding berhasil dibuat.</span>
                </div>
            `);
            
            $('#encodingInfo').show();
            $('#encodingDimensions').text(descriptor.length + ' dimensions');
            $('#encodingSample').text(faceEncoding.slice(0, 5).map(v => v.toFixed(4)).join(', ') + '...');
            
        } else {
            $('#faceStatus').html(`
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <span>Tidak ada wajah terdeteksi. Silakan upload foto yang jelas wajahnya.</span>
                </div>
            `);
            faceEncoding = null;
        }
    } catch (error) {
        console.error('Error extracting face encoding:', error);
        $('#faceStatus').html(`
            <div class="alert alert-danger">
                <i class="ti ti-x me-2"></i>
                <span>Error: ${error.message}</span>
            </div>
        `);
        faceEncoding = null;
    }
}

function uploadPhoto() {
    const formData = new FormData();
    
    // Add photo file or webcam data
    const pond = window.FilePond.find(document.querySelector('#photo'));
    const photoFile = pond ? (pond.getFile() ? pond.getFile().file : null) : null;
    
    if (photoFile) {
        formData.append('photo', photoFile);
    } else if ($('#photoPreview img').length > 0) {
        // Convert data URL to blob
        const dataUrl = $('#photoPreview img').attr('src');
        const blob = dataURLtoBlob(dataUrl);
        formData.append('photo', blob, 'webcam_photo.jpg');
    }
    
    // Add face encoding if extracted
    if (faceEncoding) {
        formData.append('face_encoding', JSON.stringify(faceEncoding));
    }
    
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    $.ajax({
        url: '{{ route("hr.pegawai.upload-photo") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                alert('Foto berhasil diupload!');
                window.location.href = '{{ route("hr.pegawai.index") }}';
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
            alert('Error: ' + message);
        }
    });
}

function dataURLtoBlob(dataURL) {
    const arr = dataURL.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while(n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {type:mime});
}
</script>
@endpush
@endsection
