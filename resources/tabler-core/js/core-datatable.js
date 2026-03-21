// resources/js/components/CustomDataTables.js

export default class CustomDataTables {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.options = {
            route: options.route || '',
            checkbox: options.checkbox || false,
            checkboxKey: options.checkboxKey || 'id',
            search: options.search !== undefined ? options.search : true,
            pageLength: options.pageLength !== undefined ? options.pageLength : true,
            columns: options.columns || [],
            ...options
        };

        this.selectedIds = new Set();
        this.table = null;
        this.stateName = 'DataTables_' + this.tableId + '_' + window.location.pathname;
        this.isRestoring = false;
        this.refreshTimeout = null;

        this.init();
    }

    init() {
        const SELECTOR = {
            table: `#${this.tableId}`,
            search: `#${this.tableId}-search`,
            pageLength: `#${this.tableId}-pageLength`,
            filterForm: `#${this.tableId}-filter`,
            selectAll: `#selectAll-${this.tableId}`,
            body: `#${this.tableId} tbody`,
            rowCheckbox: '.select-row',
        };

        this.table = $(SELECTOR.table).DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24 * 7, // 1 week
            stateSaveInterval: 0, // Save state immediately
            responsive: false, // Disable DT responsive to use native scrolling
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: this.options.route,
                data: (d) => this.addFilterData(d, SELECTOR.filterForm),
            },
            columns: this.buildColumns(),
            dom: "<'table-responsive'tr>" +
                "<'card-footer d-flex align-items-center'<'text-muted'i><'ms-auto'p>>",
            stateLoadCallback: (settings, callback) => this.loadState(callback, SELECTOR),
            stateSaveCallback: (settings, data) => this.saveState(settings, data, SELECTOR.filterForm),
        });

        // Pasang semua event listener
        this.bindEvents(SELECTOR);

        // Trigger init
        this.table.one('init', () => this.updateInfo());
        this.table.on('draw', () => this.onDraw(SELECTOR));
    }

    addFilterData(d, filterFormSelector) {
        const filterForm = document.querySelector(filterFormSelector);
        
        if (filterForm && filterForm instanceof HTMLFormElement) {
            const formData = new FormData(filterForm);
            for (const [key, value] of formData.entries()) {
                // Form data overrides default DataTables params if they exist
                d[key] = value;
            }
        }

        // Also check if there's a standalone pageLength input NOT inside the form
        const standaloneLen = document.getElementById(`${this.tableId}-pageLength`);
        if (standaloneLen && standaloneLen.value) {
            d['length'] = standaloneLen.value === 'All' ? -1 : standaloneLen.value;
        }

        const storedState = localStorage.getItem(this.stateName);
        if (storedState) {
            const state = JSON.parse(storedState);
            if (state.customFilter) {
                for (const [key, value] of Object.entries(state.customFilter)) {
                    // Backfill other custom filters if not present in the current request
                    if (d[key] === undefined) {
                        d[key] = value;
                    }
                }
            }
        }
    }

    buildColumns() {
        const cols = [];

        if (this.options.checkbox) {
            cols.push({
                data: null,
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: (data, type, row) => {
                    const id = row[this.options.checkboxKey];
                    return `<input type="checkbox" name="selected_items[]" value="${id}" class="form-check-input dt-checkboxes select-row" data-id="${id}">`;
                }
            });
        }

        this.options.columns.forEach(col => {
            const column = {
                data: col.data,
                name: col.name,
            };
            if (col.render) column.render = col.render;
            if (col.orderable !== undefined) column.orderable = col.orderable;
            if (col.searchable !== undefined) column.searchable = col.searchable;
            if (col.class !== undefined) column.className = col.class;
            cols.push(column);
        });

        return cols;
    }

    loadState(callback, SELECTOR) {
        const stored = localStorage.getItem(this.stateName);
        if (stored) {
            const state = JSON.parse(stored);
            this.isRestoring = true;
            callback(state);

            // Sync UI in next tick to ensure table instance is available
            setTimeout(() => {
                // Sync filter form
                const form = document.getElementById(`${this.tableId}-filter`);
                if (form && state.customFilter) {
                    form.dataset.isRestoring = 'true';
                    for (const [key, value] of Object.entries(state.customFilter)) {
                        const el = form.querySelector(`[name="${key}"]`);
                        if (el) {
                            $(el).val(value);
                            if ($(el).data('select2')) $(el).trigger('change.select2');
                        }
                    }
                    delete form.dataset.isRestoring;
                    this.updateActiveFilters();
                }

                // Sync page length UI - explicitly check the hidden input
                const api = this.table;
                if (api) {
                    const currentLen = api.page.len();
                    const displayLen = currentLen === -1 ? 'All' : currentLen;
                    
                    const textEl = document.getElementById(`${this.tableId}-pageLength-text`);
                    if (textEl) textEl.textContent = displayLen;

                    const inputEl = document.getElementById(`${this.tableId}-pageLength`);
                    if (inputEl) inputEl.value = displayLen;

                    const menu = document.getElementById(`${this.tableId}-pageLength-menu`);
                    if (menu) {
                        menu.querySelectorAll('.dropdown-item').forEach(item => {
                            if (item.getAttribute('data-value') == displayLen) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    }
                }
                // Give all triggered 'change' events and Select2 settled time before unguarding
                setTimeout(() => {
                    this.isRestoring = false;
                    this.updateActiveFilters();
                }, 200);
            }, 50);
        } else {
            callback(null);
        }
    }

    saveState(settings, data, filterFormSelector) {
        const filterForm = document.querySelector(filterFormSelector);
        if (filterForm && filterForm instanceof HTMLFormElement) {
            const formData = new FormData(filterForm);
            const customFilter = {};
            for (const [key, value] of formData.entries()) {
                customFilter[key] = value;
            }
            data.customFilter = customFilter;
        }

        localStorage.setItem(this.stateName, JSON.stringify(data));
    }

    bindEvents(SELECTOR) {
        const filterForm = document.querySelector(SELECTOR.filterForm);

        const refreshTable = () => {
            if (this.isRestoring) return;

            // Skip if the filter form is mid-reset (bulk clearing selects)
            if (filterForm && filterForm.dataset.isResetting) return;
            
            // Debounce the refresh to batch multiple changes (e.g. from Select2 or multi-input updates)
            clearTimeout(this.refreshTimeout);
            this.refreshTimeout = setTimeout(() => {
                if (this.isRestoring) return;

                // Sync page length from hidden input if it exists
                const pageLenEl = document.getElementById(`${this.tableId}-pageLength`);
                if (pageLenEl) {
                    const val = pageLenEl.value;
                    const len = val === 'All' ? -1 : parseInt(val);
                    if (this.table.page.len() !== len) {
                        this.table.page.len(len);
                    }
                }

                this.updateActiveFilters();
                this.table.ajax.reload();
            }, 300); // 300ms debounce to batch rapid filter changes
        };

        // Refresh Button
        const refreshBtn = document.querySelector(`#${this.tableId}-refresh`);
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                if (this.isRestoring) return;
                this.table.draw();
            });
        }

        // Search
        if (this.options.search) {
            let timeout;
            const searchInput = document.querySelector(SELECTOR.search);
            const clearBtn = document.getElementById(`${this.tableId}-clear-search`);
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    if (this.isRestoring) return;
                    clearTimeout(timeout);
                    const q = e.target.value.trim();
                    if (clearBtn) clearBtn.classList.toggle('d-none', !q);
                    timeout = setTimeout(() => this.table.search(q).draw(), 300);
                });
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        if (this.isRestoring) return;
                        searchInput.value = '';
                        clearBtn.classList.add('d-none');
                        this.table.search('').draw();
                    });
                }
            }
        }

        // Combined Filter Form & Page Length Listener
        if (filterForm) {
            filterForm.addEventListener('change', refreshTable);
            this.updateActiveFilters();
        }

        // Fallback for standalone page length if it's NOT inside a form
        const pageLenEl = document.querySelector(SELECTOR.pageLength);
        if (pageLenEl && (!filterForm || !filterForm.contains(pageLenEl))) {
            pageLenEl.addEventListener('change', refreshTable);
        }

        // Checkbox

        // Checkbox
        if (this.options.checkbox) {
            const selectAll = document.querySelector(SELECTOR.selectAll);
            if (selectAll) {
                selectAll.addEventListener('change', (e) => {
                    const checked = e.target.checked;
                    this.table.rows({ search: 'applied' }).every((rowIdx, tableLoop, rowLoop) => { // Modified here
                        const row = this.table.row(rowIdx).node(); // Modified here
                        const cb = row.querySelector(SELECTOR.rowCheckbox);
                        if (cb) {
                            cb.checked = checked;
                            const id = cb.dataset.id;
                            checked ? this.selectedIds.add(id) : this.selectedIds.delete(id);
                        }
                    });
                    this.updateSelectAllState(SELECTOR);
                });
            }

            const tbody = document.querySelector(SELECTOR.body);
            if (tbody) {
                tbody.addEventListener('change', (e) => {
                    if (e.target.matches(SELECTOR.rowCheckbox)) {
                        const id = e.target.dataset.id;
                        e.target.checked ? this.selectedIds.add(id) : this.selectedIds.delete(id);
                        this.updateSelectAllState(SELECTOR);
                    }
                });
            }
        }
    }

    updateSelectAllState(SELECTOR) {
        const total = this.table.rows({ search: 'applied' }).count();
        const checked = document.querySelectorAll(`${SELECTOR.body} ${SELECTOR.rowCheckbox}:checked`).length;
        const selectAll = document.querySelector(SELECTOR.selectAll);
        if (selectAll) selectAll.checked = total > 0 && checked === total;
    }

    updateActiveFilters() {
        const container = document.getElementById(`${this.tableId}-active-filters`);
        if (!container) return;

        container.innerHTML = '';
        const form = document.getElementById(`${this.tableId}-filter`);
        if (!form) return;

        const formData = new FormData(form);
        for (const [key, value] of formData.entries()) {
            if (value) {
                const badge = document.createElement('span');
                badge.className = 'badge badge-sm bg-primary me-1 mb-1';
                badge.innerHTML = `${key}: ${value} <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6em;" onclick="window.clearFilter('${key}', '${this.tableId}')"></button>`;
                container.appendChild(badge);
            }
        }
    }

    onDraw(SELECTOR) {
        // Restore checkboxes
        if (this.options.checkbox) {
            this.table.rows().every((rowIdx, tableLoop, rowLoop) => { // Modified here
                const row = this.table.row(rowIdx).node(); // Modified here
                const cb = row.querySelector(SELECTOR.rowCheckbox);
                if (cb) cb.checked = this.selectedIds.has(cb.dataset.id);
            });
            this.updateSelectAllState(SELECTOR);
        }
        this.updateInfo();
    }

    updateInfo() {
        const el = document.getElementById(`${this.tableId}-info`);
        if (!el || !this.table) return;

        const info = this.table.page.info();
        let start = info.start === -1 ? 0 : info.start + 1;
        let end = info.end === -1 ? 0 : info.end;
        const total = info.recordsTotal || 0;
        const filtered = info.recordsFiltered || 0;

        // Safety check for NaN (in case of corrupted state or invalid pageLength)
        if (isNaN(start)) start = 0;
        if (isNaN(end)) end = 0;

        if (filtered > 0 && start > 0) {
            if (total === filtered) {
                el.innerHTML = `Showing ${start} to ${end} of ${filtered} entries`;
            } else {
                el.innerHTML = `Showing ${start} to ${end} of ${filtered} entries (filtered from ${total} total entries)`;
            }
        } else {
            el.innerHTML = 'Showing 0 to 0 of 0 entries';
        }
    }
}
