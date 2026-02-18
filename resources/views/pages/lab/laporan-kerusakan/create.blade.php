@extends('layouts.tabler.app')

@section('title', 'Lapor Kerusakan')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Form Lapor Kerusakan" pretitle="Berkas">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.laporan-kerusakan.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('lab.laporan-kerusakan.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            
                            <x-tabler.form-select id="select-lab" name="lab_id" label="Lab" :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name])->toArray()" placeholder="Pilih Lab" required class="select2 mb-3" />

                            <x-tabler.form-select id="select-inventaris" name="inventaris_id" label="Inventaris / Alat" :options="[]" placeholder="Pilih Lab Terlebih Dahulu" required disabled class="select2 mb-3" help="Pilih alat yang rusak. Jika fasilitas umum (AC, Pintu), pilih item terkait jika ada." />

                            <x-tabler.form-textarea name="deskripsi_kerusakan" label="Deskripsi Kerusakan" rows="4" placeholder="Jelaskan detail kerusakan..." required />

                            <x-tabler.form-input type="file" name="bukti_foto" label="Bukti Foto" accept="image/*" />

                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="cancel" href="{{ route('lab.laporan-kerusakan.index') }}" />
                            <x-tabler.button type="submit" class="btn-primary" icon="bx bx-send" text="Kirim Laporan" />
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
