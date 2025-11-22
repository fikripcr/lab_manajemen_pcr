@props([
    'dataTableId' => null,
])

<div class="dataTables_toolbar mb-3">
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" id="{{ $dataTableId ? $dataTableId . '-filter' : 'global-search' }}" class="dataTable-input form-control" placeholder="Search..." onkeyup="handleDataTableFilter(this, '{{ $dataTableId }}')" />
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function handleDataTableFilter(element, tableId) {
            const searchTerm = element.value;

            if (tableId) {
                // Target specific table
                const table = $('#' + tableId).DataTable();
                table.search(searchTerm).draw();
            } else {
                // Search across all tables on the page
                $('table.dataTable').each(function() {
                    const dataTable = $(this).DataTable();
                    dataTable.search(searchTerm).draw();
                });
            }
        }
    </script>
@endpush
