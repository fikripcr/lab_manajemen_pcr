@props([
    'id' => 'pageLength',
    'selected' => '10',
    'options' => ['10', '25', '50', '100']
])

<select
    id="{{ $id }}"
    class="dataTable-input form-select"
    onchange="handlePageLengthChange(this)"
    {{ $attributes->merge(['class' => '']) }}
>
    @foreach($options as $option)
        <option value="{{ $option }}" {{ $option == $selected ? 'selected' : '' }}>
            {{ $option }} entries
        </option>
    @endforeach
</select>

@push('scripts')
<script>
    function handlePageLengthChange(element) {
        const length = parseInt(element.value);
        const tableId = element.closest('.dataTables_wrapper').previousElementSibling?.id ||
                        element.closest('.card-datatable')?.querySelector('table')?.id ||
                        'default-table';

        if (window.DataTableInstances && window.DataTableInstances[tableId]) {
            window.DataTableInstances[tableId].page.len(length).draw();
        } else {
            // If specific table not found, try to find DataTable associated with the parent container
            const tableElement = element.closest('.card-datatable')?.querySelector('table.dataTable');
            if (tableElement && $.fn.DataTable.isDataTable(tableElement)) {
                const dataTable = $(tableElement).DataTable();
                dataTable.page.len(length).draw();
            } else {
                // Fallback: apply to all DataTables on page
                $('table.dataTable').DataTable().page.len(length).draw();
            }
        }
    }
</script>
@endpush
