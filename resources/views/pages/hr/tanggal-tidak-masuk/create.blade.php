@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Add Tanggal Tidak Masuk</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('hr.tanggal-tidak-masuk.store') }}" method="POST" id="bulkForm" class="ajax-form">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Entry</h3>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3 row">
                        <label class="col-3 col-form-label required">Tahun</label>
                        <div class="col">
                            <select name="tahun" class="form-select" required>
                                @for($i = date('Y') - 1; $i <= date('Y') + 2; $i++)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <small class="form-hint">Year for all entries below.</small>
                        </div>
                    </div>

                    <div id="entries-container">
                        <!-- Initial Row -->
                        <div class="row entry-row mb-3" data-index="0">
                            <div class="col-md-5">
                                <label class="form-label required">Dates</label>
                                <input type="text" name="entries[0][dates]" class="form-control date-picker-multi" placeholder="Select dates..." required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Keterangan</label>
                                <input type="text" name="entries[0][keterangan]" class="form-control" placeholder="e.g. Libur Nasional" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-icon remove-row" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" id="add-entry">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                            Add Another Entry
                        </button>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('hr.tanggal-tidak-masuk.index') }}" class="btn btn-link link-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save All Entries</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let entryIndex = 1;
        const container = document.getElementById('entries-container');
        const addButton = document.getElementById('add-entry');

        // Initialize Flatpickr for an element
        function initFlatpickr(element) {
            if (window.flatpickr) {
                window.flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            } else {
                console.error("Flatpickr not loaded");
            }
        }

        // Initialize first row
        initFlatpickr(document.querySelector('.date-picker-multi'));

        // Add Entry Handler
        addButton.addEventListener('click', function() {
            const template = container.querySelector('.entry-row').cloneNode(true);
            
            // Updates attributes
            template.dataset.index = entryIndex;
            const inputs = template.querySelectorAll('input');
            inputs[0].name = `entries[${entryIndex}][dates]`;
            inputs[0].value = ''; // Clear value
            inputs[0].classList.remove('flatpickr-input', 'active'); // Clean flatpickr classes
            inputs[0].removeAttribute('readonly'); // Remove readonly if flatpickr added it
            
            // Remove Flatpickr instance data if any (though cloning usually strips event listeners, flatpickr modifies DOM)
            // It's safer to reconstruct the input or just fix the clone. 
            // Flatpickr wraps input in a div usually on init? No, usually just adds classes. 
            // But if mobile, it changes type. 
            // Simplest is to clear specific Flatpickr artifacts if they exist, or just replace the input HTML.
            
            inputs[1].name = `entries[${entryIndex}][keterangan]`;
            inputs[1].value = '';

            // Enable remove button
            const removeBtn = template.querySelector('.remove-row');
            removeBtn.disabled = false;
            removeBtn.addEventListener('click', function() {
                template.remove();
            });

            container.appendChild(template);
            
            // Re-init Flatpickr on new input
            initFlatpickr(inputs[0]);

            entryIndex++;
        });
    });
</script>
@endpush
@endsection
