@extends('layouts.tabler.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
@section('header')
    <x-tabler.page-header title="QR Code Generator & Scanner" pretitle="System Test" />
@endsection
        <style>
            video,
            canvas {
                max-width: 100%;
                width: 400px;
                height: auto;
                margin: 10px 0;
                border: 2px solid #ccc;
                border-radius: 8px;
            }

            #result {
                margin-top: 20px;
                padding: 10px;
                font-size: 18px;
                font-weight: bold;
                color: black;
                background: #eee;
                border-radius: 8px;
            }

            button {
                margin: 10px;
                padding: 10px 20px;
                font-size: 16px;
                border: none;
                border-radius: 8px;
                background: #007bff;
                color: white;
                cursor: pointer;
            }

            button:disabled {
                background: #999;
                cursor: not-allowed;
            }

            .flash {
                animation: flash-bg 0.8s ease;
            }

            @keyframes flash-bg {
                0% {
                    background: yellow;
                }

                100% {
                    background: #eee;
                }
            }
        </style>
        <div class="row">
            <!-- QR Code Generator Column -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <h5 class="card-header">QR Code Generator</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <x-tabler.form-input id="qrTextInput" placeholder="Enter text or URL to encode" />
                        </div>

                        <div class="mb-3">
                            <label for="qrSizeInput" class="form-label">QR Code Size (for reference only)</label>
                            <input type="number" class="form-control" id="qrSizeInput" value="200" min="100" max="500">
                        </div>

                        <x-tabler.button type="button" class="btn-primary w-100" id="generateQrBtn" text="Generate QR Code (SVG)" />

                        <div id="qrCodeContainer" class="mt-4 text-center" style="display: none;">
                            <h6>Generated QR Code:</h6>
                            <div id="qrCodeDisplay" class="d-inline-block bg-white border rounded"></div>
                            <div class="mt-2">
                                <x-tabler.button type="button" class="btn-outline-secondary btn-sm" id="downloadQrPngBtn" text="Download PNG" />
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
                        <div class="mb-4">
                            <div class="d-grid">
                                <x-tabler.button type="button" class="btn-outline-primary" id="turnOnCameraBtn" icon="bx bx-camera" text="Activate Camera Scanner" />
                            </div>
                        </div>

                        <div id="cameraContainer" class="border rounded p-2 bg-light" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Camera Preview</small>
                                <x-tabler.button type="button" class="btn-sm btn-outline-danger" id="stopCameraBtn" icon="bx bx-stop-circle" text="Stop" />
                            </div>
                            <video id="cameraPreview" autoplay  playsinline class="w-100 border rounded" style="max-height: 300px;"></video>
                        </div>

                        <div id="qrScannerResult" class="mt-4">
                            <h6 class="mb-3">Scan Results</h6>
                            <div class="border rounded p-3 bg-light">
                                <p id="qrContent" class="mb-0 text-muted fst-italic">Scanned content will appear here</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HTML5 QR & Barcode Scanner Card -->
                <div class="card mb-4">
                    <h5 class="card-header">HTML5 QR & Barcode Scanner</h5>
                    <div class="card-body">
                        <div id="html5qr-code-container"></div>
                        <div class="mt-3">
                            <x-tabler.button type="button" class="btn-primary w-100" id="startHtml5QrScanner" icon="bx bx-qr-scan" text="Start HTML5 Scanner" />
                            <x-tabler.button type="button" class="btn-danger w-100 mt-2" id="stopHtml5QrScanner" style="display: none;" icon="bx bx-stop-circle" text="Stop HTML5 Scanner" />
                        </div>
                        <div id="html5qr-result" class="mt-3">
                            <h6 class="mb-2">HTML5 Scan Results</h6>
                            <div class="border rounded p-3 bg-light">
                                <p id="html5qr-content" class="mb-0 text-muted fst-italic">Scanned content will appear here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // QR Code Generator elements
            const qrTextInput = document.getElementById('qrTextInput');
            const qrSizeInput = document.getElementById('qrSizeInput');
            const generateQrBtn = document.getElementById('generateQrBtn');
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const qrCodeDisplay = document.getElementById('qrCodeDisplay');
            const downloadQrPngBtn = document.getElementById('downloadQrPngBtn');

            // QR Scanner elements for new UI
            const qrScannerFile = document.getElementById('qrScannerFile');
            const scanUploadedQrBtn = document.getElementById('scanUploadedQrBtn');
            const turnOnCameraBtn = document.getElementById('turnOnCameraBtn');
            const stopCameraBtn = document.getElementById('stopCameraBtn');
            const cameraPreview = document.getElementById('cameraPreview');
            const cameraContainer = document.getElementById('cameraContainer');
            const qrContent = document.getElementById('qrContent');

            // Current QR code data URL for download
            let qrdataUrl = '';

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
                            qrdataUrl = `data:image/png;base64,${response.data.svg}`;

                            // Display the QR code
                            qrCodeDisplay.innerHTML = `
                                <img
                                    src="${qrdataUrl}"
                                    alt="QR Code"
                                    class="img-fluid p-2"
                                    style="max-width: 100%; height: auto; background: white;"
                                />
                            `;
                            qrCodeContainer.style.display = 'block';
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
                const a = document.createElement('a');
                a.href = qrdataUrl;
                a.download = 'qrcode.png';
                a.click();
            });


            let stream = null;
            let scanning = false;
            let rafId = null;

            async function startCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    });
                    cameraPreview.srcObject = stream;
                    scanning = true;
                    qrContent.textContent = "Scanning...";
                    qrContent.className = 'mb-0 text-info fst-italic';
                    turnOnCameraBtn.disabled = true;
                    stopCameraBtn.disabled = false;
                    cameraContainer.style.display = 'block';
                    scanLoop();
                } catch (err) {
                    alert("Error accessing camera: " + err.message);
                }
            }

            function stopCamera() {
                scanning = false;
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                if (stream) {
                    cameraPreview.srcObject = null;
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                cameraPreview.pause();
                turnOnCameraBtn.disabled = false;
                stopCameraBtn.disabled = true;
                cameraContainer.style.display = 'none';
            }

            function scanLoop() {
                if (!scanning) return;

                if (cameraPreview.readyState === cameraPreview.HAVE_ENOUGH_DATA) {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = cameraPreview.videoWidth;
                    canvas.height = cameraPreview.videoHeight;
                    ctx.drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        qrContent.textContent = "QR Code: " + code.data;
                        qrContent.className = 'mb-0 text-success fst-italic flash';
                        stopCamera();
                        setTimeout(() => qrContent.classList.remove("flash"), 1000);
                        return;
                    }
                }
                rafId = requestAnimationFrame(scanLoop);
            }

            turnOnCameraBtn.addEventListener("click", startCamera);
            stopCameraBtn.addEventListener("click", stopCamera);

            // HTML5 QR Code Scanner functionality
            let html5QrCode = null;
            const startHtml5QrScannerBtn = document.getElementById('startHtml5QrScanner');
            const stopHtml5QrScannerBtn = document.getElementById('stopHtml5QrScanner');
            const html5QrResult = document.getElementById('html5qr-content');

            startHtml5QrScannerBtn.addEventListener('click', function() {
                if (html5QrCode) {
                    // If already running, just show container
                    document.getElementById('html5qr-code-container').style.display = 'block';
                    return;
                }

                // Create container if it doesn't exist
                const container = document.getElementById('html5qr-code-container');
                container.innerHTML = '<div id="html5qr-reader" style="width: 100%;"></div>';

                // Initialize HTML5 QR Code scanner
                html5QrCode = new Html5Qrcode("html5qr-reader");

                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E,
                        Html5QrcodeSupportedFormats.CODABAR,
                        Html5QrcodeSupportedFormats.DATA_MATRIX,
                        Html5QrcodeSupportedFormats.AZTEC
                    ]
                };

                startHtml5QrScannerBtn.style.display = 'none';
                stopHtml5QrScannerBtn.style.display = 'block';

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText, decodedResult) => {
                        html5QrResult.textContent = decodedText;
                        html5QrResult.className = 'mb-0 text-success fst-italic';

                        // Stop scanning after successful scan
                        if (html5QrCode) {
                            html5QrcodeScanner.clear();
                            html5QrCode.stop().then(() => {
                                html5QrCode = null;
                                startHtml5QrScannerBtn.style.display = 'block';
                                stopHtml5QrScannerBtn.style.display = 'none';
                            }).catch(err => {
                                console.error('Failed to stop html5qr scanner', err);
                            });
                        }
                    },
                    (errorMessage) => {
                        // console.log("QR Code not found: " + errorMessage);
                    }
                ).catch(err => {
                    console.error('Error starting html5qr scanner', err);
                    html5QrResult.textContent = "Error: " + err.message;
                    html5QrResult.className = 'mb-0 text-danger fst-italic';

                    startHtml5QrScannerBtn.style.display = 'block';
                    stopHtml5QrScannerBtn.style.display = 'none';
                });
            });

            stopHtml5QrScannerBtn.addEventListener('click', function() {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        html5QrCode = null;
                        startHtml5QrScannerBtn.style.display = 'block';
                        stopHtml5QrScannerBtn.style.display = 'none';
                        document.getElementById('html5qr-code-container').style.display = 'none';
                    }).catch(err => {
                        console.error('Failed to stop html5qr scanner', err);
                    });
                }
            });
        });
    </script>
@endpush
