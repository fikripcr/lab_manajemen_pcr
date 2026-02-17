@extends('layouts.public.app')

@section('content')
    <!-- Request Software Section -->
    <section id="request-software" class="request-software section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Request Software</h2>
            <p>Ajukan permintaan software yang dibutuhkan di laboratorium</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body p-5">
                            @include('components.guest.alerts')

                            <form id="softwareRequestForm" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="nama_software" class="form-label fw-bold">Nama Software <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_software') is-invalid @enderror" id="nama_software" name="nama_software" value="{{ old('nama_software') }}" placeholder="Masukkan nama software yang diinginkan">
                                    @error('nama_software')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="alasan" class="form-label fw-bold">Alasan / Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alasan') is-invalid @enderror" id="alasan" name="alasan" rows="4" placeholder="Jelaskan alasan atau keperluan penggunaan software ini">{{ old('alasan') }}</textarea>
                                    @error('alasan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mata Kuliah Terkait</label>
                                    <select class="form-select select2 @error('mata_kuliah_ids') is-invalid @enderror" name="mata_kuliah_ids[]" multiple="multiple" id="mata_kuliah_select2">
                                        <!-- Options will be loaded via AJAX -->
                                        @foreach (old('mata_kuliah_ids', []) as $id)
                                            <option value="{{ $id }}" selected>{{ \App\Models\MataKuliah::find($id)?->kode ?? 'Unknown' }} - {{ \App\Models\MataKuliah::find($id)?->nama ?? 'Unknown' }}</option>
                                        @endforeach
                                    </select>
                                    @error('mata_kuliah_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ketik untuk mencari dan pilih satu atau lebih mata kuliah terkait (opsional)</small>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="bi bi-send me-1"></i> Kirim Permintaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Request Software Section -->

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const $mataKuliahSelect = $('#mata_kuliah_select2');
                const $form = $('#softwareRequestForm');
                const $submitBtn = $('#submitBtn');

                /**
                 * Inisialisasi Select2 untuk pencarian Mata Kuliah
                 */
                $mataKuliahSelect.select2({
                    placeholder: 'Ketik untuk mencari mata kuliah...',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('public.matakuliah.search') }}",
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: data => ({
                            results: (data.results || data).map(item => ({
                                id: item.id,
                                text: `${item.text}`
                            }))
                        }),
                        cache: true
                    }
                });

                /**
                 * Handle form submit via AJAX
                 */
                $form.on('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const originalBtnText = $submitBtn.html();

                    // Tombol loading state
                    $submitBtn.prop('disabled', true)
                        .html('<i class="bi bi-arrow-repeat me-1 spin"></i> Mengirim...');

                    fetch("{{ route('public.store-software-request') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Response not OK');
                            return response.json();
                        })
                        .then(data => {
                            showAlert(
                                data.success ?
                                'success' :
                                'danger',
                                data.success ?
                                'Berhasil! Permintaan software berhasil dikirim dan sedang menunggu persetujuan.' :
                                (data.message || 'Terjadi kesalahan saat mengirim permintaan.')
                            );

                            if (data.success) {
                                $form.trigger('reset');
                                $mataKuliahSelect.val(null).trigger('change');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'Terjadi kesalahan saat mengirim permintaan.');
                        })
                        .finally(() => {
                            $submitBtn.prop('disabled', false).html(originalBtnText);
                        });
                });

                /**
                 * Fungsi reusable untuk menampilkan alert bootstrap
                 */
                function showAlert(type, message) {
                    const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-1"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
                    $('.card-body .alert').remove(); // hapus alert lama agar tidak menumpuk
                    $('.card-body').prepend(alertHtml);
                }
            });
        </script>
    @endpush
@endsection
