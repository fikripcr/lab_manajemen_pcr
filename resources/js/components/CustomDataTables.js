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
            responsive: false, // Disable DT responsive to use native scrolling
            order: [[0, 'desc']],
            pageLength: this.options.pageLengthValue || 10,
            ajax: {
                url: this.options.route,
                data: (d) => this.addFilterData(d, SELECTOR.filterForm),
            },
            columns: this.buildColumns(),
            dom: "<'table-responsive'tr>" +
                "<'card-footer d-flex align-items-center'<'text-muted'i><'ms-auto'p>>",
            stateLoadCallback: (settings, callback) => this.loadState(callback, SELECTOR),
            stateSaveCallback: (settings, data) => this.saveState(data, SELECTOR.filterForm),
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
                d[key] = value;
            }
        }

        const storedState = localStorage.getItem(this.stateName);
        if (storedState) {
            const state = JSON.parse(storedState);
            if (state.customFilter) {
                for (const [key, value] of Object.entries(state.customFilter)) {
                    if (d[key] === undefined || d[key] === '') {
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
            if (col.className) column.className = col.className;
            cols.push(column);
        });

        return cols;
    }

    loadState(callback, SELECTOR) {
        const stored = localStorage.getItem(this.stateName);
        if (stored) {
            const state = JSON.parse(stored);

            // Set the page length from the stored state before loading
            if (state.length !== undefined) {
                this.options.pageLengthValue = state.length;
            }

            callback(state);

            setTimeout(() => {
                // Sync UI
                const pageLengthEl = $(`#${this.tableId}-pagelength`);
                if (pageLengthEl.length) pageLengthEl.val(state.length);

                if (this.options.search && state.search?.search) {
                    $(`#${this.tableId}-search`).val(state.search.search);
                }

                const form = document.getElementById(`${this.tableId}-filter`);
                if (form && state.customFilter) {
                    for (const [key, value] of Object.entries(state.customFilter)) {
                        const el = form.querySelector(`[name="${key}"]`);
                        if (el) el.value = value;
                    }
                }
            }, 0);
        } else {
            callback(null);
        }
    }

    saveState(data, filterFormSelector) {
        const filterForm = document.querySelector(filterFormSelector);
        if (filterForm && filterForm instanceof HTMLFormElement) {
            const formData = new FormData(filterForm);
            data.customFilter = Object.fromEntries(formData.entries());
        }

        // Ensure page length is preserved in state
        data.length = this.table.page.len();

        localStorage.setItem(this.stateName, JSON.stringify(data));
    }

    bindEvents(SELECTOR) {
        // Refresh
        const refreshBtn = document.querySelector(`#${this.tableId}-refresh`);
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.table.draw());
        }

        // Search
        if (this.options.search) {
            let timeout;
            const searchInput = document.querySelector(SELECTOR.search);
            const clearBtn = document.getElementById(`${this.tableId}-clear-search`);
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(timeout);
                    const q = e.target.value.trim();
                    if (clearBtn) clearBtn.classList.toggle('d-none', !q);
                    timeout = setTimeout(() => this.table.search(q).draw(), 300);
                });
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        clearBtn.classList.add('d-none');
                        this.table.search('').draw();
                    });
                }
            }
        }

        // Page Length
        if (this.options.pageLength) {
            const pageLenEl = document.querySelector(SELECTOR.pageLength);
            if (pageLenEl) {
                // Atur nilai awal berdasarkan state atau nilai default DataTables
                const initialPageLength = this.table.context[0]._iDisplayLength;
                pageLenEl.value = initialPageLength;

                pageLenEl.addEventListener('change', (e) => {
                    this.table.page.len(parseInt(e.target.value)).draw();
                });
            }
        }

        // Filter Form
        const filterForm = document.querySelector(SELECTOR.filterForm);
        if (filterForm) {
            filterForm.addEventListener('change', () => {
                this.updateActiveFilters();
                this.table.ajax.reload();
            });
            this.updateActiveFilters();
        }

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
        const start = info.start === -1 ? 0 : info.start + 1;
        const end = info.end === -1 ? 0 : info.end;
        const total = info.recordsTotal || 0;
        const filtered = info.recordsFiltered || 0;

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
