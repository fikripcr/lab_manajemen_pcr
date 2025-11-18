<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center w-100">
                    <i class="bx bx-search fs-4 lh-0 me-2"></i>
                    <input type="text" class="form-control border-0 shadow-none flex-grow-1" id="global-search-input" placeholder="Search users, roles, permissions..." aria-label="Search..." autocomplete="off" style="border-radius: 0.375rem;" />
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
    <script>
        // Global search functionality
        let globalSearchTimer;
        let currentSearchTerm = '';

        // Initialize global search
        function initGlobalSearch() {
            // Open global search modal
            window.openGlobalSearchModal = function() {
                $('#globalSearchModal').modal('show');
                // Focus on search input after modal is shown
                $('#globalSearchModal').on('shown.bs.modal', function() {
                    $('#global-search-input').focus();
                });
            };

            // Clear search input
            $('#clear-search-btn').on('click', function() {
                $('#global-search-input').val('').focus();
                $('#search-results-container').html(`
            <div class="text-center py-4">
                <i class="bx bx-search fs-1"></i>
                <p class="mb-0">Enter a term to search across all sections</p>
            </div>`);
            });

            // Handle search input
            $('#global-search-input').on('keyup', function(e) {
                const searchTerm = $(this).val().trim();

                // Escape key closes the modal
                if (e.key === 'Escape') {
                    $('#globalSearchModal').modal('hide');
                    return;
                }

                // Clear previous timer
                if (globalSearchTimer) {
                    clearTimeout(globalSearchTimer);
                }

                // If search term is empty, show initial state
                if (!searchTerm) {
                    $('#search-results-container').html(`
                        <div class="text-center py-4">
                            <i class="bx bx-search fs-1"></i>
                            <p class="mb-0">Enter a term to search across all sections</p>
                        </div>
                    `);
                    return;
                }

                // Set new timer for search
                globalSearchTimer = setTimeout(() => {
                    performGlobalSearch(searchTerm);
                }, 500); // 500ms delay to avoid excessive API calls
            });

            // Close modal and clear search when modal is hidden
            $('#globalSearchModal').on('hidden.bs.modal', function() {
                $('#global-search-input').val('');
                $('#search-results-container').html(`
                    <div class="text-center py-4">
                        <i class="bx bx-search fs-1"></i>
                        <p class="mb-0">Enter a term to search across all sections</p>
                    </div>
                `);
                currentSearchTerm = '';
            });

            // Enable Enter key to initiate search
            $('#global-search-input').on('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = $(this).val().trim();
                    if (searchTerm) {
                        performGlobalSearch(searchTerm);
                    }
                }
            });

            // Allow Ctrl+K or Cmd+K to open search modal
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    $('#globalSearchModal').modal('show');
                }
            });
        }

        function performGlobalSearch(term) {
            if (term === currentSearchTerm) {
                return; // Avoid duplicate requests for same term
            }

            currentSearchTerm = term;

            // Show loading indicator
            $('#search-results-container').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0">Searching...</p>
        </div>
    `);

            // Perform AJAX search
            $.ajax({
                url: '{{ route('global-search') }}',
                method: 'GET',
                data: {
                    q: term
                },
                success: function(response) {
                    displaySearchResults(response, term);
                },
                error: function(xhr, status, error) {
                    console.error('Global search error:', error);
                    $('#search-results-container').html(`
                <div class="alert alert-danger" role="alert">
                    <i class="bx bx-error-circle me-1"></i> An error occurred while searching. Please try again.
                </div>
            `);
                }
            });
        }

        function displaySearchResults(results, searchTerm) {
            let html = '<div class="search-results">';

            // Count total results
            const totalResults = (results.users?.length || 0) +
                (results.roles?.length || 0) +
                (results.permissions?.length || 0);

            if (totalResults === 0) {
                html += `
            <div class="text-center py-4">
                <i class="bx bx-search fs-1 text-muted"></i>
                <p class="mb-0">No results found for "<strong>` + searchTerm + `</strong>"</p>
                <small class="text-muted">Try different keywords or check spelling</small>
            </div>
        `;
            } else {
                html += '<p class="mb-3">Found <strong>' + totalResults + '</strong> results for "<strong>' + searchTerm + '</strong>"</p>';

                // Users results
                if (results.users && results.users.length > 0) {
                    html += `
                <div class="mb-4">
                    <h6 class="mb-3"><i class="bx bx-user me-1"></i> Users (` + results.users.length + `)</h6>
                    <div class="list-group list-group-flush">
            `;
                    results.users.forEach(function(user) {
                        html += `
                    <a href="` + user.url + `" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs">
                                    <img src="` + (user.avatar || '/assets-admin/img/avatars/1.png') + `" alt class="rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">` + user.name + `</h6>
                                <small class="text-muted">` + user.email + `</small>
                            </div>
                        </div>
                    </a>
                `;
                    });
                    html += '</div></div>';
                }

                // Roles results
                if (results.roles && results.roles.length > 0) {
                    html += `
                <div class="mb-4">
                    <h6 class="mb-3"><i class="bx bx-shield me-1"></i> Roles (` + results.roles.length + `)</h6>
                    <div class="list-group list-group-flush">
            `;
                    results.roles.forEach(function(role) {
                        html += `
                    <a href="` + role.url + `" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-shield"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">` + role.name + `</h6>
                                <small class="text-muted">Role Management</small>
                            </div>
                        </div>
                    </a>
                `;
                    });
                    html += '</div></div>';
                }

                // Permissions results
                if (results.permissions && results.permissions.length > 0) {
                    html += `
                <div class="mb-4">
                    <h6 class="mb-3"><i class="bx bx-lock me-1"></i> Permissions (` + results.permissions.length + `)</h6>
                    <div class="list-group list-group-flush">
            `;
                    results.permissions.forEach(function(permission) {
                        html += `
                    <a href="` + permission.url + `" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs bg-label-info rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-lock"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">` + permission.name + `</h6>
                                <small class="text-muted">Permission</small>
                            </div>
                        </div>
                    </a>
                `;
                    });
                    html += '</div></div>';
                }
            }

            html += '</div>';
            $('#search-results-container').html(html);
        }

        // Initialize global search when document is ready
        $(document).ready(function() {
            initGlobalSearch();
        });
    </script>
@endpush>
