@props(['id', 'route', 'columns', 'search' => true, 'pageLength' => true, 'Checkbox' => false, 'checkboxKey' => 'id'])

@push('css')
    <!-- DataTables CSS - using sys assets -->
    <link rel="stylesheet" href="{{ asset('assets-sys/css/custom-datatable.css') }}" />
@endpush

<div class="table-responsive">
    <table id="{{ $id }}" class="table" style="width:100%">
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
            const stateName = 'DataTables_' + TABLE_ID + '_' + window.location.pathname;
            const SELECTOR = {
                table: `#${TABLE_ID}`,
                search: `#${TABLE_ID}-search`,
                pageLength: `#${TABLE_ID}-pageLength`,
                selectAll: `#selectAll-${TABLE_ID}`,
                body: `#${TABLE_ID} tbody`,
                rowCheckbox: '.select-row'
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
                    url: '{{ $route }}'
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
                        }, 0); // Delay kecil agar DataTable selesai render
                    } else {
                        callback(null);
                    }
                },
                stateSaveCallback: function(settings, data) {
                    localStorage.setItem(stateName, JSON.stringify(data));
                }
            });

            // === SEARCH DENGAN DEBOUNCE ===
            @if ($search)
                let searchTimeout;
                document.querySelector(SELECTOR.search)?.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    searchTimeout = setTimeout(() => {
                        table.search(query).draw();
                    }, 300); // 300ms debounce
                });
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
