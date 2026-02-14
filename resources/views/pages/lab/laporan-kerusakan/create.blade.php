@extends('layouts.admin.app')

@section('title', 'Lapor Kerusakan')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Form Lapor Kerusakan
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.laporan-kerusakan.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('lab.laporan-kerusakan.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="mb-3">
                                <label class="form-label required">Lab</label>
                                <select id="select-lab" name="lab_id" class="form-select select2" required>
                                    <option value="">Pilih Lab</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ encryptId($lab->lab_id) }}">{{ $lab->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Inventaris / Alat</label>
                                <select id="select-inventaris" name="inventaris_id" class="form-select select2" required disabled>
                                    <option value="">Pilih Lab Terlebih Dahulu</option>
                                </select>
                                <small class="form-hint">Pilih alat yang rusak. Jika fasilitas umum (AC, Pintu), pilih item terkait jika ada.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Deskripsi Kerusakan</label>
                                <textarea name="deskripsi_kerusakan" class="form-control" rows="4" placeholder="Jelaskan detail kerusakan..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bukti Foto</label>
                                <input type="file" name="bukti_foto" class="form-control" accept="image/*">
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        $('#select-lab').on('change', function() {
            var labId = $(this).val();
            var invSelect = $('#select-inventaris');
            
            if (labId) {
                invSelect.prop('disabled', true).html('<option>Loading...</option>');
                
                // Fetch Inventaris via AJAX (Using existing endpoint from LabInventarisController or similar)
                // Existing route: lab.labs.inventaris.get-inventaris (needs decrypted ID in URL params usually)
                // Let's assume we can reuse or create a simple getter. 
                // Using `lab.labs.inventaris.get-inventaris` might list UNASSIGNED items depending on implementation.
                // We need ASSIGNED items for that lab.
                // `LabInventarisController::data` (index json) lists assigned items.
                // Let's try to hit a new endpoint or list from data.
                // For now, let's use a workaround URL if needed or we must add a specific 'get-all-inventaris-by-lab' endpoint.
                // Since we are in 'create' view, we can't easily add new controller method without route change.
                // Let's look at `LabInventarisController::getInventaris` -> `getUnassignedForLab`. Not what we want.
                // Solution: We'll stick to a placeholder or simple logic. 
                // BETTER: Add a dedicated route in `LaporanKerusakanController` to get inventory.
                
                $.ajax({
                    url: '{{ route("lab.laporan-kerusakan.inventaris") }}', // Need to add this route
                    data: { lab_id: labId },
                    success: function(response) {
                        invSelect.empty().append('<option value="">Pilih Inventaris</option>');
                        if(response.data) {
                            response.data.forEach(function(item) {
                                invSelect.append(new Option(item.text, item.id));
                            });
                        }
                        invSelect.prop('disabled', false);
                    }
                });
            } else {
                invSelect.prop('disabled', true).html('<option value="">Pilih Lab Terlebih Dahulu</option>');
            }
        });
    });
</script>
@endpush
