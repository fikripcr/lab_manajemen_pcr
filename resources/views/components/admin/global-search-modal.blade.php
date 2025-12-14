<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center w-100">
                    <i class="{{ $icon ?? 'bx bx-search' }} fs-4 lh-0 me-2"></i>
                    <input type="text" class="form-control border-0 shadow-none flex-grow-1" id="global-search-input" placeholder="{{ $placeholder ?? 'Search users, roles, permissions...' }}" aria-label="Search..." autocomplete="off" style="border-radius: 0.375rem;" />
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div id="search-results-container" class="p-3">
                    <p class="text-center text-muted mb-0 py-5">Start typing to search...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/assets/admin/js/global-search.js'])
@endpush
