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
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="downloadQrPngBtn">Download PNG</button>
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
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // QR Code Generator elements
            const qrTextInput = document.getElementById('qrTextInput');
            const qrSizeInput = document.getElementById('qrSizeInput');
            const generateQrBtn = document.getElementById('generateQrBtn');
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const qrCodeDisplay = document.getElementById('qrCodeDisplay');
            const downloadQrPngBtn = document.getElementById('downloadQrPngBtn');

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
                    showErrorMessage('Error!', 'Please enter text or URL to encode');
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
                            showSuccessMessage('Success!', response.data.message);
                        } else {
                            // Error message
                            showErrorMessage('Error!', response.data.message || 'Failed to generate QR code');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error generating QR code:', error);

                        // Error message
                        showErrorMessage('Error!', 'Failed to generate QR code: ' + (error.response?.data?.message || error.message));
                    })
                    .finally(function() {
                        // Reset button state
                        generateQrBtn.innerHTML = 'Generate QR Code (SVG)';
                        generateQrBtn.disabled = false;
                    });
            });

            // Download QR Code as PNG
            downloadQrPngBtn.addEventListener('click', function() {
                // Create an in-memory canvas to convert SVG to PNG
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Create an image from the SVG content
                const img = new Image();
                const svgBlob = new Blob([currentSvgContent], {type: 'image/svg+xml'});
                const url = URL.createObjectURL(svgBlob);

                img.onload = function() {
                    // Set canvas dimensions to match the image
                    canvas.width = img.width || 300;
                    canvas.height = img.height || 300;

                    // Draw the image on the canvas
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    // Convert to PNG and download
                    const pngUrl = canvas.toDataURL('image/png');

                    const a = document.createElement('a');
                    a.href = pngUrl;
                    a.download = 'qrcode-' + new Date().getTime() + '.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    URL.revokeObjectURL(url);
                };

                img.onerror = function() {
                    showErrorMessage('Error!', 'Failed to convert QR code to PNG');
                    URL.revokeObjectURL(url);
                };

                img.src = url;
            });

            // Scan uploaded QR code
            scanUploadedQrBtn.addEventListener('click', function() {
                if (!qrScannerFile.files.length) {
                    showErrorMessage('Error!', 'Please select a QR code image to scan');
                    return;
                }

                const file = qrScannerFile.files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        // Create a canvas with appropriate dimensions
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Set canvas dimensions to match the image
                        canvas.width = img.naturalWidth || img.width;
                        canvas.height = img.naturalHeight || img.height;

                        // Draw the image on the canvas
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                        // Get image data from the canvas
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                        // Use the jsQR library to decode the image
                        const code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code) {
                            qrContent.textContent = code.data;
                            qrContent.className = 'text-success';
                        } else {
                            qrContent.textContent = 'Could not decode QR code';
                            qrContent.className = 'text-danger';
                        }
                    };
                    img.onerror = function() {
                        qrContent.textContent = 'Error loading image';
                        qrContent.className = 'text-danger';
                        showErrorMessage('Error!', 'Could not load image file');
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
                        showErrorMessage('Error!', 'Could not access camera: ' + err.message);
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

                // Create a canvas to capture video frame for scanning
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Set canvas dimensions to match video
                canvas.width = cameraPreview.videoWidth;
                canvas.height = cameraPreview.videoHeight;

                // Draw current video frame to the canvas
                ctx.drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);

                // Get image data from the canvas
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                // Scan for QR code in the image data
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    qrContent.textContent = code.data;
                    qrContent.className = 'text-success';

                    // Stop scanning after successful scan
                    if (videoStream) {
                        videoStream.getTracks().forEach(track => track.stop());
                        videoStream = null;
                    }

                    showSuccessMessage('QR Code Scanned!', 'Successfully decoded QR code: ' + code.data);
                } else {
                    // Continue scanning
                    requestAnimationFrame(scanVideoFrame);
                }
            }
        });
    </script>
@endpush
