@props(['id', 'columns' => [], 'search' => true, 'pageLength' => true, 'order' => []])

<div class="table-responsive">
    <table id="{{ $id }}" class="table table-sm table-striped table-vcenter card-table mb-0">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th @if(isset($column['orderable']) && $column['orderable'] === false) data-orderable="false" @endif>
                        {{ $column['title'] ?? $column['label'] ?? $column['name'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use same pattern as server-side datatable - lazy load
            if (window.loadDataTables) {
                window.loadDataTables().then(() => {
                    // jQuery and DataTables are now loaded
                    if (typeof $ !== 'undefined' && typeof $.fn.dataTable !== 'undefined') {
                        const table = $('#{{ $id }}').DataTable({
                            // Client-side processing
                            serverSide: false,
                            processing: false,
                            
                            // Features
                            searching: {{ $search ? 'true' : 'false' }},
                            ordering: true,
                            paging: true,
                            info: true,
                            pageLength: {{ is_numeric($pageLength) ? $pageLength : 10 }},
                            
                            // Responsive - disable DT responsive, use native scrolling
                            responsive: false,
                            
                            // DOM layout - match server-side styling
                            dom: "<'table-responsive'tr>" +
                                 "<'card-footer d-flex align-items-center'<'text-muted'i><'ms-auto'p>>",
                            
                            // Language
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Search...",
                                lengthMenu: "_MENU_",
                                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                infoEmpty: "No entries available",
                                infoFiltered: "(filtered from _MAX_ total entries)",
                                paginate: {
                                    first: "First",
                                    last: "Last",
                                    next: "Next",
                                    previous: "Previous"
                                }
                            },
                            
                            // Column definitions from props
                            columnDefs: [
                                @foreach ($columns as $index => $column)
                                    @if (isset($column['orderable']) && $column['orderable'] === false)
                                        { targets: {{ $index }}, orderable: false },
                                    @endif
                                    @if (isset($column['searchable']) && $column['searchable'] === false)
                                        { targets: {{ $index }}, searchable: false },
                                    @endif
                                @endforeach
                            ],
                            
                            @if (!empty($order))
                            // Default ordering
                            order: @json($order),
                            @else
                            // Default order by first column descending if not specified
                            order: [[0, 'desc']],
                            @endif
                        });

                        // Bind search input
                        @if ($search)
                        const searchInput = document.getElementById('{{ $id }}-search');
                        const clearBtn = document.getElementById('{{ $id }}-clear-search');
                        if (searchInput) {
                            let timeout;
                            searchInput.addEventListener('input', function(e) {
                                clearTimeout(timeout);
                                const query = e.target.value.trim();
                                
                                // Toggle clear button
                                if (clearBtn) {
                                    clearBtn.classList.toggle('d-none', !query);
                                }
                                
                                // Debounce search
                                timeout = setTimeout(() => {
                                    table.search(query).draw();
                                }, 300);
                            });
                        }
                        @endif

                        // Bind page length selector
                        @if ($pageLength)
                        const pageLengthSelect = document.getElementById('{{ $id }}-pageLength');
                        if (pageLengthSelect) {
                            pageLengthSelect.addEventListener('change', function(e) {
                                const value = e.target.value;
                                if (value === 'All') {
                                    table.page.len(-1).draw();
                                } else {
                                    table.page.len(parseInt(value)).draw();
                                }
                            });
                        }
                        @endif
                    }
                });
            }
        });
    </script>
@endpush
