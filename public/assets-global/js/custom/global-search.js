// Global Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search-input');
    const modalSearchInput = document.getElementById('modal-search-input');
    const modal = document.getElementById('global-search-modal');
    const modalResultsContainer = modal ? modal.querySelector('.search-results-container') : null;

    if (searchInput) {
        // Show modal when clicking the search input
        searchInput.addEventListener('click', function() {
            const bootstrapModal = new bootstrap.Modal(modal);

            // Listen for the modal shown event to ensure the element is visible
            modal.addEventListener('shown.bs.modal', function() {
                if (modalSearchInput) {
                    // Focus the input and select all text to make it clear it's ready for input
                    modalSearchInput.focus();
                    modalSearchInput.select();

                    // Add a visual indicator that the input is focused
                    modalSearchInput.style.boxShadow = '0 0 0 0.2rem rgba(67, 94, 190, 0.25)';
                    setTimeout(() => {
                        if (modalSearchInput) {
                            modalSearchInput.style.boxShadow = '';
                        }
                    }, 1000); // Remove after 1 second
                }
            });

            bootstrapModal.show();
        });

        if (modalSearchInput && modalResultsContainer) {
            let searchTimeout;

            modalSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                const query = this.value.trim();

                if (query === '') {
                    modalResultsContainer.innerHTML = '<p class="text-center text-muted mb-0 py-5">Start typing to search...</p>';
                    return;
                }

                // Use debounce to limit API calls
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300); // 300ms delay
            });

            function performSearch(query) {
                axios.get(`${window.appRoutes.globalSearch}?q=${encodeURIComponent(query)}`)
                    .then(function(response) {
                        displaySearchResults(response.data);
                    })
                    .catch(function(error) {
                        console.error('Global search error:', error);
                        modalResultsContainer.innerHTML = '<p class="text-center text-danger mb-0 py-5">Error performing search</p>';
                    });
            }

            function displaySearchResults(data) {
                let html = '';

                // Display users
                if (data.users && data.users.length > 0) {
                    html += '<div class="mb-3"><h6 class="text-muted mb-2">Users</h6>';
                    data.users.forEach(user => {
                        html += `
                            <a class="dropdown-item d-flex align-items-center mb-1 rounded" href="${user.url}" style="text-decoration: none;">
                                <div class="avatar avatar-xs flex-shrink-0 me-3">
                                    <img src="${user.email ? 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5' : 'https://ui-avatars.com/api/?name=User&color=7F9CF5'}"
                                         class="rounded-circle"
                                         alt="${user.name}"
                                         width="24"
                                         height="24">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${user.name}</div>
                                    <small class="text-muted">${user.email}</small>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                }

                // Display roles
                if (data.roles && data.roles.length > 0) {
                    html += '<div class="mb-3"><h6 class="text-muted mb-2">Roles</h6>';
                    data.roles.forEach(role => {
                        html += `
                            <a class="dropdown-item d-flex align-items-center mb-1 rounded" href="${role.url}" style="text-decoration: none;">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-shield-alt text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${role.name}</div>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                }

                // Display permissions
                if (data.permissions && data.permissions.length > 0) {
                    html += '<div class="mb-3"><h6 class="text-muted mb-2">Permissions</h6>';
                    data.permissions.forEach(permission => {
                        html += `
                            <a class="dropdown-item d-flex align-items-center mb-1 rounded" href="${permission.url}" style="text-decoration: none;">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-key text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${permission.name}</div>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                }

                // If no results
                if ((!data.users || data.users.length === 0) &&
                    (!data.roles || data.roles.length === 0) &&
                    (!data.permissions || data.permissions.length === 0)) {
                    html = '<p class="text-center text-muted mb-0 py-5">No results found</p>';
                }

                modalResultsContainer.innerHTML = html;
            }
        }
    }
});