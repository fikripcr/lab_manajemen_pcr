@props(['id', 'route', 'columns', 'search' => true, 'pageLengthSelector' => '#pageLength', 'order' => [[0, 'desc']], 'withCheckbox' => false, 'checkboxKey' => 'id'])

@push('css')
       <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/libs/datatables/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin') }}/css/custom-datatable.css" />
@endpush

<div class="table-responsive">
    <table id="{{ $id }}" class="table" style="width:100%">
        <thead>
            <tr>
                @if ($withCheckbox)
                    <th style="min-width: 40px;">
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
    <script src="{{ asset('assets-admin/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets-admin/libs/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/custom/datatable-utils.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent reinitialization if already initialized
            if ($.fn.DataTable.isDataTable('#{{ $id }}')) {
                return;
            }

            // Initialize DataTable
            const table = $('#{{ $id }}').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ $route }}',
                    data: function(d) {
                        // Capture custom search from the filter component if search is enabled
                        @if ($search)
                            const searchValue = $('#globalSearch-{{ $id }}').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }
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
                order: {!! json_encode($order) !!},
                pageLength: 10,
                responsive: true,
                dom: 'rtip',
                // Restore state as early as possible
                stateLoadCallback: function(settings, callback) {
                    const tableId = '{{ $id }}';
                    const stateName = 'DataTables_' + tableId + '_' + window.location.pathname;
                    const storedState = localStorage.getItem(stateName);

                    if (storedState) {
                        const state = JSON.parse(storedState);
                        // Update the page length selector with saved value
                        const savedPageLength = state.length;
                        if (savedPageLength) {
                            const pageLengthSelector = '{{ $pageLengthSelector }}';
                            $(pageLengthSelector).val(savedPageLength);
                        }
                        // Update the search input with saved value from DataTable's internal search
                        @if ($search)
                        const savedSearch = state.search && state.search.search;
                        if (savedSearch) {
                            $('#globalSearch-{{ $id }}').val(savedSearch);
                        }
                        @endif
                        callback(state);
                    } else {
                        callback(null);
                    }
                },
                stateSaveCallback: function(settings, data) {
                    const tableId = '{{ $id }}';
                    const stateName = 'DataTables_' + tableId + '_' + window.location.pathname;
                    localStorage.setItem(stateName, JSON.stringify(data));
                }
            });

            // Handle "Select All" checkbox
            @if ($withCheckbox)
                let isSelectAllChecked = false;
                const selectAllCheckbox = $('#selectAll-{{ $id }}');

                // Create a Set to store selected IDs
                const selectedIds = new Set();

                // Handle individual row checkbox clicks
                $('#{{ $id }} tbody').on('change', '.select-row', function() {
                    const id = $(this).data('id');

                    if (this.checked) {
                        selectedIds.add(id);
                    } else {
                        selectedIds.delete(id);
                    }

                    // Update the select all checkbox state
                    const totalRows = table.rows({
                        search: 'applied'
                    }).count();
                    const checkedRows = $('.select-row:checked', '#{{ $id }} tbody').length;

                    selectAllCheckbox.prop('checked', checkedRows === totalRows && totalRows > 0);
                    isSelectAllChecked = (checkedRows === totalRows && totalRows > 0);
                });

                // Handle "Select All" checkbox click
                selectAllCheckbox.on('change', function() {
                    const rows = table.rows({
                        search: 'applied'
                    }).nodes();
                    const isChecked = this.checked;

                    // Update checkboxes in current view
                    $('.select-row', rows).each(function() {
                        const id = $(this).data('id');
                        $(this).prop('checked', isChecked);

                        if (isChecked) {
                            selectedIds.add(id);
                        } else {
                            selectedIds.delete(id);
                        }
                    });

                    isSelectAllChecked = isChecked;
                });

                // Restore checkbox states when the table is redrawn
                table.on('draw', function() {
                    const rows = table.rows().nodes();

                    // Update "Select All" checkbox based on selectedIds
                    let totalRows = 0;
                    let checkedCount = 0;

                    $('.select-row', rows).each(function() {
                        const id = $(this).data('id');
                        if (selectedIds.has(id)) {
                            $(this).prop('checked', true);
                            checkedCount++;
                        } else {
                            $(this).prop('checked', false);
                        }
                        totalRows++;
                    });

                    // Update select all checkbox state
                    if (totalRows > 0) {
                        selectAllCheckbox.prop('checked', checkedCount === totalRows);
                        isSelectAllChecked = (checkedCount === totalRows);
                    } else {
                        selectAllCheckbox.prop('checked', false);
                        isSelectAllChecked = false;
                    }
                });

                // Store selected IDs in a global variable accessible from outside
                window.getSelectedIds = function() {
                    return Array.from(selectedIds);
                };
            @endif

            // Setup common DataTable behaviors
            setupCommonDataTableBehaviors(table, {
                @if ($search)
                    searchInputSelector: '#globalSearch-{{ $id }}',
                @endif
                pageLengthSelector: '{{ $pageLengthSelector }}'
            });

            // Define the setupCommonDataTableBehaviors function
            if (typeof setupCommonDataTableBehaviors === 'undefined') {
                window.setupCommonDataTableBehaviors = function(table, options) {
                    // Handle page length change
                    $(document).on('change', options.pageLengthSelector, function() {
                        const pageLength = parseInt($(this).val());

                        // Set page length to -1 for "All" option to show all records
                        if (pageLength === -1) {
                            table.page.len(-1).draw();
                        } else {
                            table.page.len(pageLength).draw();
                        }
                    });

                    // Update the page length selector when table state changes
                    table.on('draw', function() {
                        const pageLength = table.page.len();
                        $(options.pageLengthSelector).val(pageLength);
                    });

                    // On the first draw after state load, ensure page length is properly set
                    table.one('draw', function() {
                        const pageLength = table.page.len();
                        $(options.pageLengthSelector).val(pageLength);
                    });
                };
            }
        });
    </script>
@endpush
