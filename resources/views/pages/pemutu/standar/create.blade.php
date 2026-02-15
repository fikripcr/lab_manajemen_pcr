@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Create Standard Indicator
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('pemutu.standar.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="standar">Standard (Indikator Standar)</option>
                            <option value="performa">Performance (Indikator Performa)</option>
                        </select>
                        <small class="form-hint">Performance indicators are usually derived from Standard indicators, but can be standalone.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Standard Document</label>
                        <select id="dokumen_id" class="form-select" required>
                            <option value="">Select Document...</option>
                            @foreach($dokumens as $dok)
                                <option value="{{ $dok->dokumen_id }}">{{ $dok->kode }} - {{ $dok->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub-Document (DokSub) / Statement</label>
                        <select name="doksub_id" id="doksub_id" class="form-select" required disabled>
                            <option value="">Select Document First...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Indicator Text</label>
                        <textarea name="indikator" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <input type="text" name="target" class="form-control" required placeholder="e.g., 100%, 5 Documents, etc.">
                    </div>
                    
                    <input type="hidden" name="parent_id" value="">

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Create Indicator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dokumenSelect = document.getElementById('dokumen_id');
        const dokSubSelect = document.getElementById('doksub_id');

        dokumenSelect.addEventListener('change', function() {
            const dokId = this.value;
            dokSubSelect.innerHTML = '<option value="">Loading...</option>';
            dokSubSelect.disabled = true;

            if (dokId) {
                fetch(`{{ url('pemutu/dok-subs') }}/${dokId}/data`) // Check if this route returns json list or datatable
                // The route 'dok-subs.data' points to DokSubController@data which likely returns Datatables.
                // We need a simple API. Let's use the method we added to IndikatorStandarController@getDokSubs
                
                fetch(`{{ url('pemutu/standar/get-dok-subs') }}/${dokId}`)
                    .then(response => response.json())
                    .then(data => {
                        dokSubSelect.innerHTML = '<option value="">Select Sub-Document...</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.doksub_id;
                            option.textContent = item.judul.substring(0, 100) + (item.judul.length > 100 ? '...' : '');
                            dokSubSelect.appendChild(option);
                        });
                        dokSubSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        dokSubSelect.innerHTML = '<option value="">Error fetching data</option>';
                    });
            } else {
                dokSubSelect.innerHTML = '<option value="">Select Document First...</option>';
                dokSubSelect.disabled = true;
            }
        });
    });
</script>
@endpush
@endsection
