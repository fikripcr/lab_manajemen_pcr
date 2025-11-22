@props(['id', 'route', 'columns', 'search' => true, 'pageLengthSelector' => '#pageLength', 'withCheckbox' => false, 'checkboxKey' => 'id'])

@push('css')
    <!-- DataTables CSS - using sys assets -->
    <link rel="stylesheet" href="{{ asset('assets-sys/css/custom-datatable.css') }}" />
@endpush

<div class="table-responsive">
    <table id="{{ $id }}" class="table" style="width:100%">
        <thead>
            <tr>
                @if ($withCheckbox)
                    <th style="min-width: 30px;">
                        <input type="checkbox" id="selectAll-{{ $id }}" class="form-check-input dt-checkboxes">
                    </th>
                @endif
                @foreach ($columns as $column)
                    <th>{{ $column['title'] ?? $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        @if ($withCheckbox)
            <tbody></tbody>
        @endif
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const TABLE_ID = '{{ $id }}';
            const SELECTOR = {
                table: `#${TABLE_ID}`,
                search: `#${TABLE_ID}-search`,
                pageLength: `{{ $pageLengthSelector }}`,
                selectAll: `#selectAll-${TABLE_ID}`,
                body: `#${TABLE_ID} tbody`,
                rowCheckbox: '.select-row'
            };
            const stateName = 'DataTables_' + TABLE_ID + '_' + window.location.pathname;


            // Initialize DataTable
            const table = $('#{{ $id }}').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searchDelay: 1000,
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: {
                    url: '{{ $route }}',
                    data: function(d) {
                        @if ($search)
                            const searchValue = document.querySelector(SELECTOR.search)?.value || '';
                            if (searchValue) d.search.value = searchValue;
                        @endif
                    }
                },
                columns: [
                    @if ($withCheckbox)
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
                    bottomStart: ['info'],
                    bottomEnd: ['paging'],
                },
                // // Restore state as early as possible
                stateLoadCallback: function(settings, callback) {
                    const storedState = localStorage.getItem(stateName);

                    if (storedState) {
                        const state = JSON.parse(storedState);
                        // Update the page length selector with saved value
                        const savedPageLength = state.length;
                        $('#{{ $id }}-pagelength').val(savedPageLength);

                        // Update the search input with saved value from DataTable's internal search
                        @if ($search)
                            const savedSearch = state.search && state.search.search;
                            if (savedSearch) {
                                $('#{{ $id }}-search').val(savedSearch);
                            }
                        @endif
                        callback(state);
                    } else {
                        callback(null);
                    }
                },
                stateSaveCallback: function(settings, data) {
                    localStorage.setItem(stateName, JSON.stringify(data));
                }
            });

            // Sync page length selector on draw
            table.on('draw', function() {
                const pageLengthEl = document.querySelector(SELECTOR.pageLength);
                if (pageLengthEl) {
                    pageLengthEl.value = table.page.len();
                }
            });

            // === SEARCH ===
            @if ($search)
                document.querySelector(SELECTOR.search)?.addEventListener('input', function() {
                    table.search(this.value).draw();
                });
            @endif

            // Handle "Select All" checkbox
            @if ($withCheckbox)
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

            // Simpan instance ke window untuk akses eksternal
            window['DT_' + TABLE_ID] = table;
        });
    </script>
@endpush
