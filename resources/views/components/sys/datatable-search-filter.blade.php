@props([
    'dataTableId' => null,
])

<div class="input-group">
    <span class="input-group-text"><i class="bx bx-search"></i></span>
    <input type="text" id="{{$dataTableId . '-search'}}"
        class="form-control"
        placeholder="Search..."
        onkeyup="handleDataTableSearch(this, '{{ $dataTableId }}')"
    />
</div>

@push('scripts')
    <script>
        function handleDataTableSearch(element, tableId) {
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
