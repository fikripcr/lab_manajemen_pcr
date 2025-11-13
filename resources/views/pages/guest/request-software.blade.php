@extends('layouts.guest.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0">Request Software</h2>
                        <p class="mb-0">Ajukan permintaan software yang dibutuhkan</p>
                    </div>
                    <div class="card-body p-5">
                        @include('components.alerts')
                        
                        <form id="softwareRequestForm" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="nama_software" class="form-label fw-bold">Nama Software <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_software') is-invalid @enderror" 
                                       id="nama_software" name="nama_software" 
                                       value="{{ old('nama_software') }}" 
                                       placeholder="Masukkan nama software yang diinginkan" required>
                                @error('nama_software')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="alasan" class="form-label fw-bold">Alasan / Keperluan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                         id="alasan" name="alasan" 
                                         rows="4" 
                                         placeholder="Jelaskan alasan atau keperluan penggunaan software ini" 
                                         required>{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mata Kuliah Terkait</label>
                                <select class="form-select select2 @error('mata_kuliah_ids') is-invalid @enderror" 
                                        name="mata_kuliah_ids[]" 
                                        multiple="multiple" 
                                        id="mata_kuliah_select2">
                                    <!-- Options will be loaded via AJAX -->
                                    @foreach(old('mata_kuliah_ids', []) as $id)
                                        <option value="{{ $id }}" selected>{{ \App\Models\MataKuliah::find($id)?->kode ?? 'Unknown' }} - {{ \App\Models\MataKuliah::find($id)?->nama ?? 'Unknown' }}</option>
                                    @endforeach
                                </select>
                                @error('mata_kuliah_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ketik untuk mencari dan pilih satu atau lebih mata kuliah terkait (opsional)</small>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bx bx-send me-1"></i> Kirim Permintaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 with AJAX
    $('#mata_kuliah_select2').select2({
        placeholder: 'Ketik untuk mencari mata kuliah...',
        allowClear: true,
        width: '100%',
        theme: "bootstrap-5",
        ajax: {
            url: '{{ route('guest.mata-kuliah.search') }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
    
    // Handle form submission with AJAX
    const form = document.getElementById('softwareRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Mengirim...';
        
        fetch('{{ route('guest.store-software-request') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitText;
            
            if(data.success) {
                showSuccessMessage('Berhasil!', 'Permintaan software berhasil dikirim dan sedang menunggu persetujuan.');
                
                // Reset form
                form.reset();
                $('#mata_kuliah_select2').val(null).trigger('change');
            } else {
                showErrorMessage('Error!', data.message || 'Terjadi kesalahan saat mengirim permintaan.');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitText;
            showErrorMessage('Error!', 'Terjadi kesalahan saat mengirim permintaan.');
            console.error('Error:', error);
        });
    });
});
</script>
@endsection