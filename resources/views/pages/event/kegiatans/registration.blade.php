@extends('layouts.public.app')

@section('content')
<div class="container-tight py-4">
    <div class="text-center mb-4">
        <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('static/logo.svg') }}" height="36" alt="">
        </a>
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Buku Tamu Kegiatan</h2>
            <p class="text-muted text-center mb-4">Silakan isi identitas Anda untuk mengikuti kegiatan: <br><strong>{{ $kegiatan->judul_Kegiatan }}</strong></p>
            
            <form id="form-registration" action="{{ route('Kegiatan.Kegiatans.registration.store', $kegiatan->hashid) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-tabler.form-input name="nama_tamu" label="Nama Lengkap" placeholder="Masukkan nama lengkap" required="true" />
                </div>
                <div class="mb-3">
                    <x-tabler.form-input name="instansi" label="Instansi / Organisasi" placeholder="Masukkan instansi (opsional)" />
                </div>
                <div class="mb-3">
                    <x-tabler.form-textarea name="keperluan" label="Keperluan" rows="2" placeholder="Masukkan keperluan (opsional)" />
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ambil Foto</label>
                        <div class="border rounded bg-light p-2 text-center" style="min-height: 200px; position: relative;">
                            <video id="webcam" class="w-100 rounded" autoplay playsinline style="display: none;"></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <img id="photo-preview" class="w-100 rounded" style="display: none;">
                            
                            <div id="webcam-placeholder" class="py-5">
                                <i class="ti ti-camera icon-lg text-muted"></i>
                                <p class="small text-muted mb-0">Kamera belum aktif</p>
                            </div>
                            
                            <div class="mt-2">
                                <x-tabler.button type="button" id="btn-start-camera" class="btn-sm btn-outline-primary" icon="ti ti-video" text="Aktifkan Kamera" />
                                <x-tabler.button type="button" id="btn-capture" class="btn-sm btn-primary" style="display: none;" icon="ti ti-camera" text="Ambil Foto" />
                                <x-tabler.button type="button" id="btn-retake" class="btn-sm btn-outline-warning" style="display: none;" icon="ti ti-refresh" text="Ambil Ulang" />
                            </div>
                        </div>
                        <input type="hidden" name="foto" id="input-foto">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanda Tangan</label>
                        <div class="border rounded bg-light p-2">
                            <canvas id="signature-pad" class="w-100 border rounded" style="touch-action: none; background-color: #fff; min-height: 200px;"></canvas>
                            <div class="mt-2 text-center">
                                <x-tabler.button type="button" id="btn-clear-sig" class="btn-sm btn-outline-warning" icon="ti ti-eraser" text="Hapus TTD" />
                            </div>
                        </div>
                        <input type="hidden" name="ttd" id="input-ttd">
                    </div>
                </div>

                <div class="form-footer">
                    <x-tabler.button type="submit" class="btn-primary w-100" icon="ti ti-device-floppy" text="Simpan Data & Masuk" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    .signature-pad {
        cursor: crosshair;
    }
</style>
@endpush

@push('scripts')
{{-- SignaturePad and SweetAlert are already bundled in public.js if using @vite --}}
{{-- However, if SignaturePad is not in public.js, we keep the CDN but wrap it correctly --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
    $(function () {
        // --- Signature Pad Logic ---
        const canvasSig = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvasSig, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Resize signature pad
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvasSig.width = canvasSig.offsetWidth * ratio;
            canvasSig.height = canvasSig.offsetHeight * ratio;
            canvasSig.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        $(window).on('resize', resizeCanvas);
        resizeCanvas();

        $('#btn-clear-sig').on('click', function () {
            signaturePad.clear();
        });

        // --- Webcam Logic ---
        const video = document.getElementById('webcam');
        const canvasPhoto = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const webcamPlaceholder = document.getElementById('webcam-placeholder');
        const btnStart = $('#btn-start-camera');
        const btnCapture = $('#btn-capture');
        const btnRetake = $('#btn-retake');
        const inputFoto = $('#input-foto');
        
        let stream = null;

        btnStart.on('click', async function () {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" }, 
                    audio: false 
                });
                video.srcObject = stream;
                $(video).show();
                $(webcamPlaceholder).hide();
                btnStart.hide();
                btnCapture.show();
            } catch (err) {
                console.error("Error accessing webcam: ", err);
                if (typeof showErrorMessage === 'function') {
                    showErrorMessage('Error', 'Gagal mengakses kamera: ' + err.message);
                } else {
                    Swal.fire('Error', 'Gagal mengakses kamera: ' + err.message, 'error');
                }
            }
        });

        btnCapture.on('click', function () {
            const context = canvasPhoto.getContext('2d');
            canvasPhoto.width = video.videoWidth;
            canvasPhoto.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvasPhoto.width, canvasPhoto.height);
            
            const dataUrl = canvasPhoto.toDataURL('image/jpeg');
            $(photoPreview).attr('src', dataUrl).show();
            $(video).hide();
            btnCapture.hide();
            btnRetake.show();
            inputFoto.val(dataUrl);
            
            // Stop webcam stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        btnRetake.on('click', function () {
            $(photoPreview).hide();
            inputFoto.val('');
            btnStart.trigger('click');
            btnRetake.hide();
        });

        // --- Form Submission ---
        $('#form-registration').on('submit', function (e) {
            e.preventDefault();

            if (signaturePad.isEmpty()) {
                if (typeof showWarningMessage === 'function') {
                    showWarningMessage('Peringatan', 'Silakan isi tanda tangan Anda.');
                } else {
                    Swal.fire('Peringatan', 'Silakan isi tanda tangan Anda.', 'warning');
                }
                return;
            }

            // Set signature data
            $('#input-ttd').val(signaturePad.toDataURL());

            const form = $(this);
            const data = form.serialize();

            if (typeof showLoadingMessage === 'function') {
                showLoadingMessage('Mohon tunggu...', 'Sedang menyimpan data Anda.');
            } else {
                Swal.fire({
                    title: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            }

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: data,
                success: function (response) {
                    if (typeof handleAjaxResponse === 'function') {
                        handleAjaxResponse(response, function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada sistem.';
                    if (typeof showErrorMessage === 'function') {
                        showErrorMessage('Oops!', message);
                    } else {
                        Swal.fire('Oops!', message, 'error');
                    }
                }
            });
        });
    });
</script>
@endpush
