<x-tabler.form-modal
    title="Tambah Tanggal Libur"
    :route="route('hr.tanggal-libur.store')"
    method="POST"
>
    <x-tabler.flash-message />
    
    <div class="mb-3">
        <x-tabler.form-select name="tahun" label="Tahun" required="true">
            @for($i = date('Y') - 1; $i <= date('Y') + 2; $i++)
                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </x-tabler.form-select>
        <small class="form-hint" style="margin-top: -1rem; display: block;">Tahun berlakunya tanggal libur.</small>
    </div>

    <!-- Headers for dynamic inputs -->
    <div class="row mb-1">
        <div class="col-md-5">
            <label class="form-label required mb-0">Tanggal Libur</label>
        </div>
        <div class="col-md-6">
            <label class="form-label required mb-0">Keterangan Libur</label>
        </div>
        <div class="col-md-1"></div>
    </div>

    <div id="entries-container">
        <!-- Initial Row -->
        <div class="row entry-row mb-2" data-index="0">
            <div class="col-md-5">
                <x-tabler.form-input name="entries[0][dates]" class="date-picker-multi mb-0" placeholder="Pilih tanggal..." required="true" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input name="entries[0][keterangan]" class="mb-0" placeholder="e.g. Libur Nasional" required="true" />
            </div>
            <div class="col-md-1 d-flex">
                <x-tabler.button class="btn-danger btn-icon w-100 remove-row" icon="ti ti-trash" disabled />
            </div>
        </div>
    </div>

    <div class="mt-3 text-end">
        <x-tabler.button type="button" class="btn-secondary btn-sm" id="add-entry" icon="ti ti-plus" text="Tambah Baris" />
    </div>

</x-tabler.form-modal>

<script>
    (function () {
        let entryIndex = 1;
        const container = document.getElementById('entries-container');
        const addButton = document.getElementById('add-entry');

        function initDatepicker(element) {
            if (window.loadFlatpickr) {
                window.loadFlatpickr().then((fp) => {
                    fp(element, {
                        mode: "multiple",
                        dateFormat: "Y-m-d",
                        allowInput: true
                    });
                }).catch(err => {
                    console.error("Flatpickr failed to load", err);
                });
            } else if (window.flatpickr) {
                window.flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            } else {
                console.error("Flatpickr not loaded");
            }
        }

        if (container) {
            initDatepicker(container.querySelector('.date-picker-multi'));
        }

        if (addButton) {
            addButton.addEventListener('click', function() {
                const template = container.querySelector('.entry-row').cloneNode(true);
                
                template.dataset.index = entryIndex;
                const inputs = template.querySelectorAll('input');
                
                // Clear flatpickr classes and attributes
                inputs[0].name = `entries[${entryIndex}][dates]`;
                inputs[0].value = ''; 
                inputs[0].classList.remove('flatpickr-input', 'active'); 
                inputs[0].removeAttribute('readonly'); 
                
                const newDateInput = document.createElement('input');
                newDateInput.type = 'text';
                newDateInput.name = `entries[${entryIndex}][dates]`;
                newDateInput.className = 'form-control date-picker-multi mb-0';
                newDateInput.placeholder = 'Pilih tanggal...';
                newDateInput.required = true;
                
                const dateParent = inputs[0].parentNode;
                if (dateParent.classList.contains('flatpickr-wrapper')) {
                    dateParent.parentNode.replaceChild(newDateInput, dateParent);
                } else {
                    inputs[0].parentNode.replaceChild(newDateInput, inputs[0]);
                }

                inputs[1].name = `entries[${entryIndex}][keterangan]`;
                inputs[1].value = '';
                // Ensure class mb-0 is there to prevent bottom margin shifting
                inputs[1].classList.add('mb-0');

                // Enable remove button
                const removeBtn = template.querySelector('.remove-row');
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    template.remove();
                });

                container.appendChild(template);
                initDatepicker(newDateInput);
                entryIndex++;
            });
        }
    })();
</script>
