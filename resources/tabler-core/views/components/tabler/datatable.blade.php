<!-- resources/views/components/custom-datatable.blade.php -->
@props(['id', 'route' => null, 'url' => null, 'columns', 'search' => true, 'pageLength' => true, 'checkbox' => false, 'checkboxKey' => 'id'])

@php
    $finalRoute = $route ?? $url;
@endphp


<div class="table-responsive">
    <table id="{{ $id }}" class="table table-sm table-striped table-vcenter card-table mb-0 dataTable">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ ($column['title'] ?? $column['name']) === 'No' ? '#' : ($column['title'] ?? $column['name']) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@if($attributes->has('ajax-load'))
    <script>
        (function() {
            const initTable = function() {
                const options = {
                    route: '{{ $finalRoute }}',
                    checkbox: {{ $checkbox ? 'true' : 'false' }},
                    checkboxKey: '{{ $checkboxKey }}',
                    search: {{ $search ? 'true' : 'false' }},
                    pageLength: {{ $pageLength ? 'true' : 'false' }},
                    columns: @json($columns)
                };

                if (window.loadDataTables) {
                    window.loadDataTables().then((CustomDataTables) => {
                        const dataTableInstance = new CustomDataTables('{{ $id }}', options);
                        window['DT_{{ $id }}'] = dataTableInstance;
                    });
                }
            };
            
            // If checking readyState in inline script might be redundant if inserted via jQuery, 
            // but safe to keep. jQuery executes scripts immediately.
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTable);
            } else {
                initTable();
            }
        })();
    </script>
@else
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const options = {
                    route: '{{ $finalRoute }}',
                    checkbox: {{ $checkbox ? 'true' : 'false' }},
                    checkboxKey: '{{ $checkboxKey }}',
                    search: {{ $search ? 'true' : 'false' }},
                    pageLength: {{ $pageLength ? 'true' : 'false' }},
                    columns: @json($columns)
                };

                if (window.loadDataTables) {
                    window.loadDataTables().then((CustomDataTables) => {
                        const dataTableInstance = new CustomDataTables('{{ $id }}', options);
                        window['DT_{{ $id }}'] = dataTableInstance;
                    });
                }
            });
        </script>
    @endpush
@endif
