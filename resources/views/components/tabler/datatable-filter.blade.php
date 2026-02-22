@props([
    'dataTableId' => null,
    'useCollapse' => true,
])

@if($useCollapse)
    <!-- Dropdown Filter Pattern (Hover) -->
    <div class="dropdown" style="position: static;">
        <button type="button" class="btn btn-primary-lt dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <i class="ti ti-filter me-1"></i> Filter
            <span id="{{ $dataTableId }}-filter-count" class="badge bg-primary text-white ms-1" style="display: none;">0</span>
        </button>
        
        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 400px; position: absolute; z-index: 1050;">
            <form id="{{ $dataTableId }}-filter">
                <div class="row g-3">
                    {{$slot}}
                </div>
                <div class="mt-3 pt-3 border-top">
                    <button type="button" class="btn btn-primary-lt btn-sm w-100" id="{{ $dataTableId }}-reset-filter">
                        <i class="ti ti-x me-1"></i> Reset 
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <!-- Inline Filter Pattern (Original) -->
    <form id="{{ $dataTableId }}-filter">
        <div class="d-flex flex-wrap gap-2 mb-2">
            {{$slot}}
        </div>

        <!-- Active filters as badges -->
        <div id="{{ $dataTableId }}-active-filters" class="mb-2">
            <!-- Active filter badges will be displayed here -->
        </div>
    </form>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataTableId = '{{ $dataTableId }}';
    const useCollapse = {{ $useCollapse ? 'true' : 'false' }};
    
    if (useCollapse) {
        initDropdownFilter(dataTableId);
    }
});

function initDropdownFilter(dataTableId) {
    const resetBtn = document.getElementById(`${dataTableId}-reset-filter`);
    const filterForm = document.getElementById(`${dataTableId}-filter`);
    const filterCount = document.getElementById(`${dataTableId}-filter-count`);
    
    if (!resetBtn || !filterForm) return;
    
    // Auto-apply filter on change
    const filterInputs = filterForm.querySelectorAll('input, select, textarea');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            const table = $(`#${dataTableId}`).DataTable();
            if (table) {
                table.ajax.reload();
                updateFilterCount();
            }
        });
    });
    
    // Reset filter
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        // Reset select2 if exists
        $(filterForm).find('select').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).val('').trigger('change');
            } else {
                $(this).val('').trigger('change');
            }
        });
        const table = $(`#${dataTableId}`).DataTable();
        if (table) {
            table.ajax.reload();
            updateFilterCount();
        }
    });
    
    // Update filter count badge (only count non-empty and non-"all" values)
    function updateFilterCount() {
        const inputs = filterForm.querySelectorAll('input, select, textarea');
        let count = 0;
        
        inputs.forEach(input => {
            const value = input.value ? input.value.trim() : '';
            // Count only if value is not empty and not a placeholder/all option
            if (value && value !== '' && value !== 'all' && value !== '0') {
                count++;
            }
        });
        
        if (count > 0) {
            filterCount.textContent = count;
            filterCount.style.display = 'inline-block';
        } else {
            filterCount.style.display = 'none';
        }
    }
    
    // Initial count
    setTimeout(() => {
        updateFilterCount();
    }, 100);
}
</script>
@endpush
