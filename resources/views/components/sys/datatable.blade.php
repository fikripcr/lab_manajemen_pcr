@props(['id', 'route', 'columns', 'search' => true, 'pageLength' => true, 'Checkbox' => false, 'checkboxKey' => 'id'])

@push('css')
    <!-- DataTables CSS - using sys assets -->
    <link rel="stylesheet" href="{{ asset('assets-sys/css/custom-datatable.css') }}" />
@endpush

<div class="table-responsive">
    <table id="{{ $id }}" class="table" style="width:100%;">
        <thead>
            <tr>
                @if ($Checkbox)
                    <th style="min-width: 30px;">
                        <input type="checkbox" id="selectAll-{{ $id }}" class="form-check-input dt-checkboxes">
                    </th>
                @endif
                @foreach ($columns as $column)
                    <th>{{ $column['title'] ?? $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        @if ($Checkbox)
            <tbody></tbody>
        @endif
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const TABLE_ID = '{{ $id }}';
            const FILTER_FORM_ID = `${TABLE_ID}-filter`;
            const stateName = 'DataTables_' + TABLE_ID + '_' + window.location.pathname;
            const SELECTOR = {
                table: `#${TABLE_ID}`,
                search: `#${TABLE_ID}-search`,
                pageLength: `#${TABLE_ID}-pageLength`,
                selectAll: `#selectAll-${TABLE_ID}`,
                body: `#${TABLE_ID} tbody`,
                filterForm: `#${FILTER_FORM_ID}`,
                rowCheckbox: '.select-row',
            };

            // Initialize DataTable
            const table = $(SELECTOR.table).DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: {
                    url: '{{ $route }}',
                    data: function(d) {
                        // Add form filter data if filter form exists
                        const filterForm = document.querySelector(SELECTOR.filterForm);
                        if (filterForm) {
                            const formData = new FormData(filterForm);
                            for (const [key, value] of formData.entries()) {
                                d[key] = value;
                            }
                        }

                        // Also check if we have saved state with filter values
                        // (This ensures filters are applied even on first load if state exists)
                        const storedState = localStorage.getItem(stateName);
                        if (storedState) {
                            const state = JSON.parse(storedState);
                            if (state.customFilter) {
                                for (const [key, value] of Object.entries(state.customFilter)) {
                                    // Only override if not already set by form
                                    if (d[key] === undefined || d[key] === '') {
                                        d[key] = value;
                                    }
                                }
                            }
                        }
                    }
                },
                columns: [
                    @if ($Checkbox)
                        {
                            data: null,
                            name: 'checkbox',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row, meta) {
                                const id = row['{{ $checkboxKey }}'];
                                return '<input type="checkbox" name="selected_items[]" value="' + id + '" class="form-check-input dt-checkboxes select-row" data-id="' + id + '">';
                            }
                        },
                    @endif

                    @foreach ($columns as $index => $column)
                        {
                            data: '{!! $column['data'] !!}',
                            name: '{!! $column['name'] !!}',
                            @if (isset($column['render']))
                                render: {!! $column['render'] !!},
                            @endif
                            @if (isset($column['orderable']))
                                orderable: {{ $column['orderable'] ? 'true' : 'false' }},
                            @endif
                            @if (isset($column['searchable']))
                                searchable: {{ $column['searchable'] ? 'true' : 'false' }},
                            @endif
                            @if (isset($column['className']))
                                className: '{{ $column['className'] }}',
                            @endif
                        },
                    @endforeach
                ],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: null,
                    bottomEnd: null,
                    bottom: [
                        'info',
                        'paging'
                    ]
                },
                // // Restore state as early as possible
                stateLoadCallback: function(settings, callback) {
                    const storedState = localStorage.getItem(stateName);

                    if (storedState) {
                        const state = JSON.parse(storedState);
                        callback(state); // ← Biarkan DataTables restore state internalnya dulu

                        // Baru set UI setelah state dipulihkan
                        setTimeout(() => {
                            // Sync page length selector
                            const pageLengthEl = $('#{{ $id }}-pagelength');
                            if (pageLengthEl.length) {
                                pageLengthEl.val(state.length);
                            }

                            // Sync search input
                            @if ($search)
                                const searchInput = $('#{{ $id }}-search');
                                if (searchInput.length && state.search?.search) {
                                    searchInput.val(state.search.search);
                                }
                            @endif

                            // Sync filter form values if exists
                            const filterForm = document.getElementById('{{ $id }}-filter');
                            if (filterForm && state.customFilter) {
                                for (const [key, value] of Object.entries(state.customFilter)) {
                                    const element = filterForm.querySelector(`[name="${key}"]`);
                                    if (element) {
                                        element.value = value;
                                    }
                                }
                            }
                        }, 0); // Delay kecil agar DataTable selesai render
                    } else {
                        callback(null);
                    }
                },
                stateSaveCallback: function(settings, data) {
                    // Save custom filter values
                    const filterForm = document.getElementById('{{ $id }}-filter');
                    if (filterForm) {
                        const formData = new FormData(filterForm);
                        data.customFilter = Object.fromEntries(formData.entries());
                    }

                    localStorage.setItem(stateName, JSON.stringify(data));
                }
            });

            // Function to update pagination and info
            function updatePagination() {
                const pageInfo = document.getElementById(`${TABLE_ID}-info`);
                const paginationContainer = document.getElementById(`${TABLE_ID}-pagination`);

                if (pageInfo && paginationContainer) {
                    // Get current table info
                    const info = table.page.info();

                    // Debug: log the info object to see its properties
                    console.log('DataTable info:', info);

                    // Update page info text using values directly from DataTables info object
                    // Check if properties exist in the info object to prevent "undefined"
                    const start = (info.start !== undefined && info.start !== null) ? info.start + 1 : 0;
                    const end = (info.end !== undefined && info.end !== null) ? info.end : 0;
                    const total = (info.recordsTotal !== undefined && info.recordsTotal !== null) ? info.recordsTotal : 0;
                    const filtered = (info.recordsFiltered !== undefined && info.recordsFiltered !== null) ? info.recordsFiltered : 0;
                    const pageLength = (info.length !== undefined && info.length !== null) ? info.length : 0;

                    // Sometimes DataTables returns -1 when there's no data
                    const actualStart = (info.start === -1) ? 0 : info.start + 1;
                    const actualEnd = (info.end === -1) ? 0 : info.end;

                    if (table.context[0]._draw > 0) {
                        // Table has drawn at least once
                        if (filtered > 0 && actualStart > 0) {
                            if (total === filtered) {
                                pageInfo.innerHTML = `Showing ${actualStart} to ${actualEnd} of ${filtered} entries`;
                            } else {
                                pageInfo.innerHTML = `Showing ${actualStart} to ${actualEnd} of ${filtered} entries (filtered from ${total} total entries)`;
                            }
                        } else {
                            // No records match the filter
                            pageInfo.innerHTML = 'Showing 0 to 0 of 0 entries';
                        }
                    } else {
                        // Table hasn't drawn yet
                        pageInfo.innerHTML = 'Loading...';
                    }

                    // Clear current pagination
                    paginationContainer.innerHTML = '';

                    // Create pagination controls
                    const ul = document.createElement('ul');
                    ul.className = 'pagination';

                    // Previous button
                    const prevLi = document.createElement('li');
                    prevLi.className = `page-item ${info.page === 0 ? 'disabled' : ''}`;
                    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${info.page - 1}, '${TABLE_ID}'); return false;">Previous</a>`;
                    ul.appendChild(prevLi);

                    // Page buttons
                    const totalPages = info.pages;
                    const currentPage = info.page;

                    // Calculate page range to display
                    let startPage = Math.max(0, currentPage - 2);
                    let endPage = Math.min(totalPages - 1, currentPage + 2);

                    // If range is too small, expand it
                    if (endPage - startPage < 4) {
                        if (startPage === 0) {
                            endPage = Math.min(totalPages - 1, startPage + 4);
                        } else if (endPage === totalPages - 1) {
                            startPage = Math.max(0, endPage - 4);
                        }
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const li = document.createElement('li');
                        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}, '${TABLE_ID}'); return false;">${i + 1}</a>`;
                        ul.appendChild(li);
                    }

                    // Next button
                    const nextLi = document.createElement('li');
                    nextLi.className = `page-item ${info.page === totalPages - 1 || totalPages === 0 ? 'disabled' : ''}`;
                    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${info.page + 1}, '${TABLE_ID}'); return false;">Next</a>`;
                    ul.appendChild(nextLi);

                    paginationContainer.appendChild(ul);
                }
            }

            // Function to change page
            window.changePage = function(page, tableId) {
                const dataTable = $(`#${tableId}`).DataTable();
                if (page >= 0 && page < dataTable.page.info().pages) {
                    dataTable.page(page).draw('page');
                }
            };

            // Function to update the custom info element
            function updateInfo() {
                const infoElement = document.getElementById(`${TABLE_ID}-info`);
                if (infoElement) {
                    const info = table.page.info();

                    const start = (info.start === -1) ? 0 : info.start + 1;
                    const end = (info.end === -1) ? 0 : info.end;
                    const total = (info.recordsTotal !== undefined && info.recordsTotal !== null) ? info.recordsTotal : 0;
                    const filtered = (info.recordsFiltered !== undefined && info.recordsFiltered !== null) ? info.recordsFiltered : 0;

                    if (filtered > 0 && start > 0) {
                        if (total === filtered) {
                            infoElement.innerHTML = `Showing ${start} to ${end} of ${filtered} entries`;
                        } else {
                            infoElement.innerHTML = `Showing ${start} to ${end} of ${filtered} entries (filtered from ${total} total entries)`;
                        }
                    } else {
                        infoElement.innerHTML = 'Showing 0 to 0 of 0 entries';
                    }
                }
            }

            // Update table after draw
            table.on('draw', function() {
                // Hide placeholder row in case it appears again
                const placeholderRows = $(SELECTOR.table).find('tbody tr.placeholder-row');
                if (placeholderRows.length > 0) {
                    placeholderRows.hide();
                }

                // Update the custom info
                updateInfo();
            });

            // Initialize the info after table is ready
            table.one('init', function() {
                updateInfo();
            });


            // Handle form filter changes if form exists
            const filterForm = document.querySelector(SELECTOR.filterForm);
            if (filterForm) {
                // Function to update active filter badges
                function updateActiveFilterBadges() {
                    const activeFiltersContainer = document.getElementById(`${TABLE_ID}-active-filters`);
                    if (!activeFiltersContainer) return;

                    // Clear existing badges
                    activeFiltersContainer.innerHTML = '';

                    // Get current filter values from the form
                    const formData = new FormData(filterForm);
                    let hasActiveFilters = false;

                    for (const [key, value] of formData.entries()) {
                        if (value !== '') {
                            hasActiveFilters = true;

                            // Create badge element
                            const badge = document.createElement('span');
                            badge.className = 'badge badge-sm bg-primary me-1 mb-1';
                            badge.innerHTML = `
                                ${key}: ${value}
                                <button type="button" class="btn-close btn-close-white ms-1"
                                    style="font-size: 0.6em;"
                                    onclick="clearFilter('${key}', '${TABLE_ID}')"
                                    aria-label="Remove"></button>
                            `;

                            activeFiltersContainer.appendChild(badge);
                        }
                    }

                }

                // Function to clear a specific filter
                window.clearFilter = function(filterName, tableId) {
                    const filterForm = document.getElementById(`${tableId}-filter`);
                    if (filterForm) {
                        const filterElement = filterForm.querySelector(`[name="${filterName}"]`);
                        if (filterElement) {
                            filterElement.value = '';

                            // Trigger change event to update the table
                            const event = new Event('change', { bubbles: true });
                            filterForm.dispatchEvent(event);
                        }
                    }
                };

                // Function to clear all filters
                window.clearAllFilters = function(tableId) {
                    const filterForm = document.getElementById(`${tableId}-filter`);
                    if (filterForm) {
                        // Reset all form elements
                        const selects = filterForm.querySelectorAll('select');
                        selects.forEach(select => {
                            select.value = '';
                        });

                        const inputs = filterForm.querySelectorAll('input');
                        inputs.forEach(input => {
                            if (input.type === 'text' || input.type === 'search') {
                                input.value = '';
                            }
                        });

                        // Trigger change event to update the table
                        const event = new Event('change', { bubbles: true });
                        filterForm.dispatchEvent(event);
                    }
                };

                // Add event listener for filter form changes
                filterForm.addEventListener('change', function() {
                    // Update the active filter badges
                    updateActiveFilterBadges();

                    // Reload the DataTable when filter values change
                    table.ajax.reload();
                });

                // Initialize the active filter badges after a short delay
                setTimeout(updateActiveFilterBadges, 100);
            }

            // === SEARCH DENGAN DEBOUNCE ===
            @if ($search)
                let searchTimeout;
                const searchInput = document.querySelector(SELECTOR.search);
                const clearSearchBtn = document.getElementById(`${TABLE_ID}-clear-search`);

                if (searchInput && clearSearchBtn) {
                    // Add event listener for search input
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        const query = this.value.trim();

                        // Show/hide clear button based on input
                        if (query !== '') {
                            clearSearchBtn.classList.remove('d-none');
                        } else {
                            clearSearchBtn.classList.add('d-none');
                        }

                        searchTimeout = setTimeout(() => {
                            table.search(query).draw();
                        }, 300); // 300ms debounce
                    });

                    // Initialize clear button visibility
                    if (searchInput.value.trim() !== '') {
                        clearSearchBtn.classList.remove('d-none');
                    } else {
                        clearSearchBtn.classList.add('d-none');
                    }
                }

                // Function to clear search
                window.clearSearch = function(tableId) {
                    const searchInput = document.getElementById(`${tableId}-search`);
                    const clearSearchBtn = document.getElementById(`${tableId}-clear-search`);

                    if (searchInput) {
                        searchInput.value = '';
                        if (clearSearchBtn) {
                            clearSearchBtn.classList.add('d-none');
                        }
                        table.search('').draw();
                    }
                };
            @endif

            // === PAGE LENGTH HANDLER ===
            @if ($pageLength)
                const pageLengthEl = document.querySelector(SELECTOR.pageLength);
                if (pageLengthEl) {
                    // Set nilai awal sesuai DataTable
                    pageLengthEl.value = table.page.len();

                    // Event listener untuk perubahan
                    pageLengthEl.addEventListener('change', function() {
                        const newLength = parseInt(this.value, 10);
                        table.page.len(newLength).draw();
                    });
                }
            @endif

            // Handle "Select All" checkbox
            @if ($Checkbox)
                const selectedIds = new Set();

                // Select All
                document.querySelector(SELECTOR.selectAll)?.addEventListener('change', function() {
                    const isChecked = this.checked;
                    table.rows({
                        search: 'applied'
                    }).every(function() {
                        const row = table.row(this).node();
                        const checkbox = row.querySelector(SELECTOR.rowCheckbox);
                        if (checkbox) {
                            checkbox.checked = isChecked;
                            const id = checkbox.dataset.id;
                            if (isChecked) selectedIds.add(id);
                            else selectedIds.delete(id);
                        }
                    });
                    updateSelectAllState();
                });
                // Individual row
                document.querySelector(SELECTOR.body).addEventListener('change', function(e) {
                    if (e.target.matches(SELECTOR.rowCheckbox)) {
                        const id = e.target.dataset.id;
                        if (e.target.checked) selectedIds.add(id);
                        else selectedIds.delete(id);
                        updateSelectAllState();
                    }
                });

                // Restore checkbox state on draw
                table.on('draw', function() {
                    table.rows().every(function() {
                        const row = table.row(this).node();
                        const checkbox = row.querySelector(SELECTOR.rowCheckbox);
                        if (checkbox) {
                            const id = checkbox.dataset.id;
                            checkbox.checked = selectedIds.has(id);
                        }
                    });
                    updateSelectAllState();
                });

                function updateSelectAllState() {
                    const total = table.rows({
                        search: 'applied'
                    }).count();
                    const checked = Array.from(document.querySelectorAll(`${SELECTOR.body} ${SELECTOR.rowCheckbox}:checked`)).length;
                    const selectAll = document.querySelector(SELECTOR.selectAll);
                    if (selectAll) {
                        selectAll.checked = total > 0 && checked === total;
                    }
                }
            @endif

            const refreshBtn = document.querySelector(`#${TABLE_ID}-refresh`);
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    table.draw();
                });
            }
        });
    </script>
@endpush
