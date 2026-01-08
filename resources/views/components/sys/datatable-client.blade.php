@props(['id', 'columns' => []])

@php
    // Normalize column titles
    $normalizedColumns = array_map(fn($col) => array_merge($col, [
        'title' => $col['title'] ?? $col['label'] ?? $col['name'] ?? ''
    ]), $columns);
@endphp

<div class="table-responsive">
    <table id="{{ $id }}" class="table table-sm table-striped table-vcenter card-table mb-0">
        <thead>
            <tr>
                @foreach ($normalizedColumns as $column)
                    <th>{{ $column['title'] }}</th>
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
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.loadDataTables) return;

            window.loadDataTables().then(() => {
                if (typeof $ === 'undefined' || typeof $.fn.dataTable === 'undefined') return;

                // Build column definitions
                const columnDefs = @json(collect($normalizedColumns)->map(function($col, $i) {
                    $defs = [];
                    if (isset($col['orderable']) && $col['orderable'] === false) $defs['orderable'] = false;
                    if (isset($col['searchable']) && $col['searchable'] === false) $defs['searchable'] = false;
                    return count($defs) ? array_merge(['targets' => $i], $defs) : null;
                })->filter()->values());

                // Initialize DataTable
                const table = $('#{{ $id }}').DataTable({
                    serverSide: false,
                    processing: false,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    pageLength: 10,
                    responsive: false,
                    dom: "<'table-responsive'tr><'card-footer d-flex align-items-center'<'text-muted'i><'ms-auto'p>>",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "_MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No entries available",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    },
                    columnDefs: columnDefs,
                    order: [[0, 'desc']]
                });

                // Search handler
                const searchInput = document.getElementById('{{ $id }}-search');
                const clearBtn = document.getElementById('{{ $id }}-clear-search');
                if (searchInput) {
                    let timeout;
                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(timeout);
                        const query = e.target.value.trim();
                        clearBtn?.classList.toggle('d-none', !query);
                        timeout = setTimeout(() => table.search(query).draw(), 300);
                    });
                }

                // Page length handler
                const pageLengthSelect = document.getElementById('{{ $id }}-pageLength');
                pageLengthSelect?.addEventListener('change', (e) => {
                    const len = e.target.value === 'All' ? -1 : parseInt(e.target.value);
                    table.page.len(len).draw();
                });
            });
        });
    </script>
@endpush
