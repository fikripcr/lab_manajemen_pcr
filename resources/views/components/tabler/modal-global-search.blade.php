<x-tabler.form-modal
    id="globalSearchModal"
    method="none"
    hideFooter="true"
    size="modal-lg"
    modal-body-class="p-0"
>
    <x-slot:titleSlot>
        <div class="d-flex align-items-center w-100">
            <i class="{{ $icon ?? 'bx bx-search' }} fs-4 lh-0 me-2"></i>
            <input type="text" class="form-control border-0 shadow-none flex-grow-1" id="global-search-input" placeholder="{{ $placeholder ?? 'Search users, roles, permissions...' }}" aria-label="Search..." autocomplete="off" style="border-radius: 0.375rem;" />
        </div>
    </x-slot:titleSlot>

    <div id="search-results-container" class="p-3">
        <p class="text-center text-muted mb-0 py-5">Start typing to search...</p>
    </div>
</x-tabler.form-modal>

{{-- Global search functionality is now included in the sys.js bundle --}}
