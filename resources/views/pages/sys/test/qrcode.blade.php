@extends('layouts.sys.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
            <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Test /</span> QR Code Generator & Scanner</h4>
        </div>

        <div class="row">
            <!-- QR Code Generator Column -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <h5 class="card-header">QR Code Generator</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="qrTextInput" class="form-label">Text/URL to encode</label>
                            <input type="text" class="form-control" id="qrTextInput" placeholder="Enter text or URL to encode">
                        </div>

                        <div class="mb-3">
                            <label for="qrSizeInput" class="form-label">QR Code Size (for reference only)</label>
                            <input type="number" class="form-control" id="qrSizeInput" value="200" min="100" max="500">
                        </div>

                        <button type="button" class="btn btn-primary w-100" id="generateQrBtn">
                            Generate QR Code (SVG)
                        </button>

                        <div id="qrCodeContainer" class="mt-4 text-center" style="display: none;">
                            <h6>Generated QR Code:</h6>
                            <div id="qrCodeDisplay" class="d-inline-block p-3 bg-white border rounded"></div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="downloadQrBtn">Download SVG</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="copyQrBtn">Copy SVG</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Scanner Column -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <h5 class="card-header">QR Code Scanner</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="qrScannerFile" class="form-label">Upload QR Code Image</label>
                            <input type="file" id="qrScannerFile" class="form-control" accept="image/*">
                        </div>

                        <button type="button" class="btn btn-primary w-100 mb-3" id="scanUploadedQrBtn">
                            Scan Uploaded QR Code
                        </button>

                        <div class="text-center mb-3">
                            <p>Or scan using your camera:</p>
                            <button type="button" class="btn btn-secondary" id="turnOnCameraBtn">
                                Turn on Camera
                            </button>
                        </div>

                        <div id="cameraContainer" style="display: none;">
                            <video id="cameraPreview" width="100%"></video>
                            <button type="button" class="btn btn-danger mt-2" id="stopCameraBtn">
                                Stop Camera
                            </button>
                        </div>

                        <div id="qrScannerResult" class="mt-3">
                            <h6>Scanned Content:</h6>
                            <p id="qrContent" class="text-muted">Scanned content will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // QR Code Generator elements
            const qrTextInput = document.getElementById('qrTextInput');
            const qrSizeInput = document.getElementById('qrSizeInput');
            const generateQrBtn = document.getElementById('generateQrBtn');
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const qrCodeDisplay = document.getElementById('qrCodeDisplay');
            const downloadQrBtn = document.getElementById('downloadQrBtn');
            const copyQrBtn = document.getElementById('copyQrBtn');

            // QR Scanner elements
            const qrScannerFile = document.getElementById('qrScannerFile');
            const scanUploadedQrBtn = document.getElementById('scanUploadedQrBtn');
            const turnOnCameraBtn = document.getElementById('turnOnCameraBtn');
            const stopCameraBtn = document.getElementById('stopCameraBtn');
            const cameraPreview = document.getElementById('cameraPreview');
            const cameraContainer = document.getElementById('cameraContainer');
            const qrContent = document.getElementById('qrContent');

            // Current QR code SVG content
            let currentSvgContent = '';

            // Generate QR Code functionality
            generateQrBtn.addEventListener('click', function() {
                const text = qrTextInput.value.trim();
                const size = qrSizeInput.value || 200;

                if (!text) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please enter text or URL to encode',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Show loading state
                generateQrBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> Generating...';
                generateQrBtn.disabled = true;

                // Send request to server to generate QR code as SVG
                axios.post('{{ route('sys.test.generate-qrcode') }}', {
                        text: text,
                        size: size
                    })
                    .then(function(response) {
                        if (response.data.success) {
                            currentSvgContent = atob(response.data.svg);

                            // Display the QR code
                            qrCodeDisplay.innerHTML = currentSvgContent;
                            qrCodeContainer.style.display = 'block';

                            // Success message
                            Swal.fire({
                                title: 'Success!',
                                text: response.data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Error message
                            Swal.fire({
                                title: 'Error!',
                                text: response.data.message || 'Failed to generate QR code',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(function(error) {
                        console.error('Error generating QR code:', error);

                        // Error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to generate QR code: ' + (error.response?.data?.message || error.message),
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(function() {
                        // Reset button state
                        generateQrBtn.innerHTML = 'Generate QR Code (SVG)';
                        generateQrBtn.disabled = false;
                    });
            });

            // Download QR Code as SVG
            downloadQrBtn.addEventListener('click', function() {
                if (!currentSvgContent) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'No QR code to download',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const blob = new Blob([currentSvgContent], {
                    type: 'image/svg+xml'
                });
                const url = URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.href = url;
                a.download = 'qrcode-' + new Date().getTime() + '.svg';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });

            // Copy QR Code SVG to clipboard
            copyQrBtn.addEventListener('click', function() {
                if (!currentSvgContent) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'No QR code to copy',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                navigator.clipboard.writeText(currentSvgContent)
                    .then(() => {
                        Swal.fire({
                            title: 'Copied!',
                            text: 'QR code SVG copied to clipboard',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    })
                    .catch(err => {
                        console.error('Failed to copy: ', err);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to copy QR code SVG to clipboard',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Scan uploaded QR code
            scanUploadedQrBtn.addEventListener('click', function() {
                if (!qrScannerFile.files.length) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select a QR code image to scan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const file = qrScannerFile.files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, img.width, img.height);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, canvas.width, canvas.height);

                        if (code) {
                            qrContent.textContent = code.data;
                            qrContent.className = 'text-success';

                            Swal.fire({
                                title: 'Success!',
                                text: 'QR code decoded successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            qrContent.textContent = 'Could not decode QR code';
                            qrContent.className = 'text-danger';

                            Swal.fire({
                                title: 'Error!',
                                text: 'Could not decode QR code',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    };
                    img.src = event.target.result;
                };

                reader.readAsDataURL(file);
            });

            // Camera QR scanner functionality
            let videoStream = null;

            turnOnCameraBtn.addEventListener('click', function() {
                if (videoStream) {
                    // If already active, just show the container
                    cameraContainer.style.display = 'block';
                    return;
                }

                // Access the camera
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    })
                    .then(function(stream) {
                        videoStream = stream;
                        cameraPreview.srcObject = stream;
                        cameraPreview.play();
                        cameraContainer.style.display = 'block';

                        // Start scanning
                        scanVideoFrame();
                    })
                    .catch(function(err) {
                        console.error("Error accessing camera: ", err);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Could not access camera: ' + err.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            stopCameraBtn.addEventListener('click', function() {
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                    cameraContainer.style.display = 'none';
                }
            });

            function scanVideoFrame() {
                if (!videoStream) return;

                const canvas = document.createElement('canvas');
                canvas.width = cameraPreview.videoWidth;
                canvas.height = cameraPreview.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);

                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) {
                    qrContent.textContent = code.data;
                    qrContent.className = 'text-success';

                    // Stop scanning after successful scan
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;

                    Swal.fire({
                        title: 'QR Code Scanned!',
                        text: 'Successfully decoded QR code: ' + code.data,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Continue scanning
                    requestAnimationFrame(scanVideoFrame);
                }
            }
        });
    </script>
@endpush
