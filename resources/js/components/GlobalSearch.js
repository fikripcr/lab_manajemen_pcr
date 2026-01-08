import { api } from '../api.js';

// Global search functionality using ES6 modules and vanilla JavaScript
export class GlobalSearch {
    constructor() {
        this.endpoint = '/global-search'; // fallback only
        this.timer = null;
        this.currentSearchTerm = '';

        // Cache DOM elements
        this.elements = {
            input: document.getElementById('global-search-input'),
            results: document.getElementById('search-results-container'),
            modal: document.getElementById('globalSearchModal'),
            clearBtn: document.getElementById('clear-search-btn')
        };

        this.searchResultConfig = {
            // Default configuration for search results
            users: {
                icon: 'bx bx-user',
                label: 'Users',
                itemTemplate: (item) => `
                    <a href="${item.url}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs">
                                    <img src="${item.avatar || '/assets-admin/img/avatars/1.png'}" alt class="rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">${item.email}</small>
                            </div>
                        </div>
                    </a>
                `
            },
            roles: {
                icon: 'bx bx-shield',
                label: 'Roles',
                itemTemplate: (item) => `
                    <a href="${item.url}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-shield"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">Role Management</small>
                            </div>
                        </div>
                    </a>
                `
            },
            permissions: {
                icon: 'bx bx-lock',
                label: 'Permissions',
                itemTemplate: (item) => `
                    <a href="${item.url}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-xs bg-label-info rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-lock"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">Permission</small>
                            </div>
                        </div>
                    </a>
                `
            },
            // Default templates for other categories can be added here
        };

        this.init();
    }

    init() {
        // Define global function for opening search modal
        window.openGlobalSearchModal = (endpoint = null) => {
            if (endpoint) {
                this.endpoint = endpoint;
            }
            this.openModal();
        };

        this.setupEventListeners();
    }

    openModal() {
        const { modal: modalElement, input: searchInput } = this.elements;

        if (modalElement) {
            // Bootstrap 5 modal implementation
            if (window.bootstrap && window.bootstrap.Modal) {
                const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            } else {
                // Fallback if Bootstrap modal is not available
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                document.body.classList.add('modal-open');

                if (searchInput) searchInput.focus();
            }
        }
    }

    setupEventListeners() {
        const { input: searchInput, clearBtn: clearSearchBtn, modal: modalElement } = this.elements;

        if (!searchInput) {
            console.error('Global search input not found');
            return;
        }

        // Clear search input
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                this.clearSearch();
            });
        }

        // Handle search input
        searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });

        // Handle keyboard events
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const term = e.target.value.trim();
                if (term) {
                    this.performGlobalSearch(term);
                }
            }
        });

        // Modal Specific Events
        if (modalElement) {
            // Auto focus input when modal opens
            modalElement.addEventListener('shown.bs.modal', () => {
                if (searchInput) searchInput.focus();
            });

            // Close modal and clear search when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', () => {
                this.resetSearch();
            });
        }
    }

    clearSearch() {
        if (this.elements.input) {
            this.elements.input.value = '';
        }
        this.showInitialContent();
    }

    showInitialContent() {
        const { results } = this.elements;
        if (results) {
            results.innerHTML = `
                <div class="text-center py-4">
                    <i class="bx bx-search fs-1"></i>
                    <p class="mb-0">Search for users, roles, permissions...</p>
                </div>
            `;
        }
    }

    handleSearchInput(searchTerm) {
        // Clear previous timer
        if (this.timer) {
            clearTimeout(this.timer);
        }

        // If search term is empty, show initial state
        if (!searchTerm.trim()) {
            this.showInitialContent();
            return;
        }

        // Set new timer for search
        this.timer = setTimeout(() => {
            this.performGlobalSearch(searchTerm.trim());
        }, 500); // 500ms delay to avoid excessive API calls
    }

    closeModal() {
        const { modal: modalElement } = this.elements;
        if (modalElement && window.bootstrap && window.bootstrap.Modal) {
            const modal = window.bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            } else {
                // Fallback
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
            }
        }
    }

    resetSearch() {
        const { input: searchInput, results: resultsContainer } = this.elements;

        if (searchInput) {
            searchInput.value = '';
        }

        if (resultsContainer) {
            resultsContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="bx bx-search fs-1"></i>
                    <p class="mb-0">Enter a term to search across all sections</p>
                </div>
            `;
        }

        this.currentSearchTerm = '';
    }

    async performGlobalSearch(term) {
        if (term === this.currentSearchTerm) {
            return; // Avoid duplicate requests for same term
        }

        this.currentSearchTerm = term;

        this.showLoadingIndicator();

        try {
            const response = await api.globalSearch(term);
            const results = response.data;

            // Race condition check: Ensure the result matches the current term
            if (term !== this.currentSearchTerm) {
                return;
            }

            this.displaySearchResults(results, term);
        } catch (error) {
            console.error('Global search error:', error);
            // Race condition check
            if (term !== this.currentSearchTerm) return;

            this.showError();
        }
    }

    showLoadingIndicator() {
        const { results } = this.elements;
        if (results) {
            results.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Searching...</p>
                </div>
            `;
        }
    }

    showError() {
        const { results } = this.elements;
        if (results) {
            results.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="bx bx-error-circle me-1"></i> An error occurred while searching. Please try again.
                </div>
            `;
        }
    }

    displaySearchResults(results, searchTerm) {
        let html = '<div class="search-results">';

        // Calculate total results from all categories
        let totalResults = 0;
        for (const category in results) {
            if (results[category] && Array.isArray(results[category])) {
                totalResults += results[category].length;
            }
        }

        if (totalResults === 0) {
            html += `
                <div class="text-center py-4">
                    <i class="bx bx-search fs-1 text-muted"></i>
                    <p class="mb-0">No results found for "<strong>${searchTerm}</strong>"</p>
                    <small class="text-muted">Try different keywords or check spelling</small>
                </div>`;
        } else {
            html += `<p class="mb-3">Found <strong>${totalResults}</strong> results for "<strong>${searchTerm}</strong>"</p>`;

            // Loop through all categories in the results
            for (const category in results) {
                if (results[category] && Array.isArray(results[category]) && results[category].length > 0) {
                    // Use the configuration for this category or default to generic template
                    const config = this.searchResultConfig[category] || {
                        icon: 'bx bx-search',
                        label: category.charAt(0).toUpperCase() + category.slice(1),
                        itemTemplate: (item) => `
                            <a href="${item.url}" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-xs bg-label-secondary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bx bx-search"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">${item.name || item.title || 'Item'}</h6>
                                        <small class="text-muted">${item.description || item.email || 'No description'}</small>
                                    </div>
                                </div>
                            </a>
                        `
                    };

                    html += `
                        <div class="mb-4">
                            <h6 class="mb-3"><i class="${config.icon} me-1"></i> ${config.label} (${results[category].length})</h6>
                            <div class="list-group list-group-flush">
                    `;

                    results[category].forEach(item => {
                        html += config.itemTemplate(item);
                    });

                    html += '</div></div>';
                }
            }
        }

        html += '</div>';

        const { results: resultsContainer } = this.elements;
        if (resultsContainer) {
            resultsContainer.innerHTML = html;
        }
    }

    // Method to add or update search result configuration
    addSearchConfig(category, config) {
        this.searchResultConfig[category] = config;
    }

    // Method to set the search result configuration
    setSearchConfig(config) {
        this.searchResultConfig = { ...this.searchResultConfig, ...config };
    }
}

// Initialize global search when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize with default endpoint
    window.globalSearch = new GlobalSearch();
});