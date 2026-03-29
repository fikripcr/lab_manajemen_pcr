@props([
    'dataTableId' => null,
    'type'        => 'bare',   // button, bare
    'target'      => null,     // target selector for type="button"
])

@php
    $effectiveTarget = $target ?? "#{$dataTableId}-filter-area";
@endphp

@if($type === 'button')
    <!-- Button Only Mode -->
    <button type="button" {{ $attributes->merge(['class' => 'btn btn-outline-primary']) }} data-bs-toggle="collapse" data-bs-target="{{ $effectiveTarget }}" aria-expanded="false">
        <i class="ti ti-filter me-1"></i> Filter
        <span id="{{ $dataTableId }}-filter-count" class="badge bg-primary text-white ms-1" style="display: none;">0</span>
    </button>
@elseif($type === 'bare')
    <!-- Bare Form Mode: To be used inside a collapsible container -->
    <div {{ $attributes->merge(['class' => 'card card-body border-0 border-bottom rounded-0 mb-0']) }}>
        <form id="{{ $dataTableId }}-filter">
            <div class="row g-3">
                {{$slot}}
                <div class="col-12 mt-2 d-flex justify-content-end align-items-center">
                    <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none" id="{{ $dataTableId }}-reset-filter">
                        <i class="ti ti-x me-1"></i> Reset Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        #{{ $dataTableId }}-filter .form-label {
            font-weight: 600;
            color: var(--tblr-emphasis-color);
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
    </style>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataTableId = '{{ $dataTableId }}';
    // Small delay to ensure DataTable is initialized
    setTimeout(() => {
        if (typeof initDatatableFilter === 'function') {
            initDatatableFilter(dataTableId);
        }
    }, 200);
});

if (typeof initDatatableFilter !== 'function') {
    function initDatatableFilter(dataTableId) {
        const resetBtn = document.getElementById(`${dataTableId}-reset-filter`);
        const filterForm = document.getElementById(`${dataTableId}-filter`);
        const filterCount = document.getElementById(`${dataTableId}-filter-count`);

        if (!filterForm) return;

        // Prevent double init
        if (filterForm.dataset.filterInit === 'true') return;
        filterForm.dataset.filterInit = 'true';

        // Set default "all" for selects that have it, BEFORE DataTable reads the form
        $(filterForm).find('select').each(function() {
            const $select = $(this);
            if ($select.find('option[value="all"]').length > 0) {
                $select.val('all');
            }
            // Update Select2 UI
            if ($select.data('select2')) {
                $select.trigger('change.select2');
            }
        });

        // Reset filter
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                filterForm.reset();

                // Clear state from localStorage
                const stateName = 'DataTables_' + dataTableId + '_' + window.location.pathname;
                localStorage.removeItem(stateName);

                // Guard: prevent per-select change events from triggering DataTable reload
                filterForm.dataset.isResetting = 'true';

                // Reset all selects silently (no individual change events)
                $(filterForm).find('select').each(function() {
                    const $select = $(this);
                    if ($select.find('option[value="all"]').length > 0) {
                        $select.val('all');
                    } else {
                        $select.val('');
                    }
                    // Update Select2 UI without triggering change
                    if ($select.data('select2')) {
                        $select.trigger('change.select2');
                    }
                });

                delete filterForm.dataset.isResetting;

                // Fire a SINGLE change event for core-datatable.js to pick up
                filterForm.dispatchEvent(new Event('change', { bubbles: true }));

                // Update filter count AFTER reset is complete
                window.updateFilterCount(filterForm, filterCount);
            });
        }

        // Update filter count badge - exposed globally so core-datatable can call it
        window.updateFilterCount = function(form, badge) {
            if (!badge) return;
            const inputs = form.querySelectorAll('input, select, textarea');
            let count = 0;

            inputs.forEach(input => {
                if (input.multiple) {
                    const selectedOptions = Array.from(input.selectedOptions).map(opt => opt.value).filter(v => v !== '');
                    if (selectedOptions.length > 0) count++;
                } else {
                    let value = input.value ? input.value.trim() : '';
                    if (value !== 'all' && value !== '') count++;
                }
            });

            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }

        // Setup listener for count update only (logic-free)
        filterForm.addEventListener('change', () => window.updateFilterCount(filterForm, filterCount));

        // Initial count - DELAYED to ensure Select2 and DataTable state restoration is complete
        setTimeout(() => {
            window.updateFilterCount(filterForm, filterCount);
        }, 400);
    }
}
</script>
@endpush
