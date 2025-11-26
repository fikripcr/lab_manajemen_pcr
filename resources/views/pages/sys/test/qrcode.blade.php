@extends('layouts.sys.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
            <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Test /</span> QR Code Generator & Scanner</h4>
        </div>
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
                            <div id="qrCodeDisplay" class="d-inline-block bg-white border rounded"></div>
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

                        <video id="video" autoplay playsinline></video>
                        <canvas id="canvas" hidden></canvas>

                        <div>
                            <button id="startBtn">Start Camera</button>
                            <button id="stopBtn" disabled>Stop Camera</button>
                        </div>

                        <div id="result">Scan a QR code...</div>
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

            const video = document.getElementById("video");
            const canvas = document.getElementById("canvas");
            const ctx = canvas.getContext("2d");
            const startBtn = document.getElementById("startBtn");
            const stopBtn = document.getElementById("stopBtn");
            const resultEl = document.getElementById("result");


            // Current QR code SVG content
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
                    video.srcObject = stream;
                    scanning = true;
                    resultEl.textContent = "Scanning...";
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
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
                    video.srcObject = null;
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                video.pause();
                startBtn.disabled = false;
                stopBtn.disabled = true;
            }

            function scanLoop() {
                if (!scanning) return;

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        resultEl.textContent = "QR Code: " + code.data;
                        resultEl.classList.add("flash");
                        stopCamera();
                        setTimeout(() => resultEl.classList.remove("flash"), 1000);
                        return;
                    }
                }
                rafId = requestAnimationFrame(scanLoop);
            }

            startBtn.addEventListener("click", startCamera);
            stopBtn.addEventListener("click", stopCamera);
        });
    </script>
@endpush
