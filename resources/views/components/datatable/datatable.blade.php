@props(['id', 'route', 'columns', 'search' => true, 'pageLengthSelector' => '#pageLength', 'order' => [[0, 'desc']]])

<div class="table-responsive">
    <table id="{{ $id }}" class="table" style="width:100%">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column['title'] ?? $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>
@push('scripts')
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
                dom: 'rtip'
            });

            // Setup common DataTable behaviors
            setupCommonDataTableBehaviors(table, {
                @if ($search)
                    searchInputSelector: '#globalSearch-{{ $id }}',
                @endif
                pageLengthSelector: '{{ $pageLengthSelector }}'
            });
        });
    </script>
@endpush
