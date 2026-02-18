@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
@if(!(request()->ajax() || request()->has('ajax')))
    @section('header')
    <x-tabler.page-header title="Tambah Tanggal Libur" pretitle="HR" />
    @endsection
@endif

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('hr.tanggal-libur.store') }}" method="POST" id="bulkForm" class="ajax-form">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Entry</h3>
                </div>
                <div class="card-body">
                    
                        <div class="col-12">
                            <x-tabler.form-select name="tahun" label="Tahun" required="true">
                                @for($i = date('Y') - 1; $i <= date('Y') + 2; $i++)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </x-tabler.form-select>
                            <small class="form-hint" style="margin-top: -1rem; display: block;">Year for all entries below.</small>
                        </div>

                    <div id="entries-container">
                        <!-- Initial Row -->
                        <div class="row entry-row mb-3" data-index="0">
                            <div class="col-md-5">
                                <x-tabler.form-input name="entries[0][dates]" class="date-picker-multi" placeholder="Select dates..." required="true" />
                            </div>
                            <div class="col-md-5">
                                <x-tabler.form-input name="entries[0][keterangan]" placeholder="e.g. Libur Nasional" required="true" />
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <x-tabler.button class="btn-danger btn-icon remove-row" icon="ti ti-trash" disabled />
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-tabler.button class="btn-secondary" id="add-entry" icon="ti ti-plus" text="Add Another Entry" />
                    </div>

                </div>
                <div class="card-footer text-end">
                    <x-tabler.button href="{{ route('hr.tanggal-libur.index') }}" class="btn-link link-secondary" text="Batal" />
                    <x-tabler.button type="submit" class="btn-primary" text="Save All Entries" />
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
