@props([
    'dataTableId' => null,
    'options' => ['10', '25', '50', '100'],
])

<select id="{{ $dataTableId }}-pagelength"
     class="form-select"
     onchange="handlePageLengthChange(this, '{{ $dataTableId }}')"
     {{ $attributes->merge(['class' => '']) }}>
    @foreach ($options as $option)
        <option value="{{ $option }}">
            {{ $option }}
        </option>
    @endforeach
</select>

@push('scripts')
    <script>
        function handlePageLengthChange(element, tableId) {
            const length = parseInt(element.value);

            if (tableId) {
                const table = $('#' + tableId).DataTable();
                table.page.len(length).draw();
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
