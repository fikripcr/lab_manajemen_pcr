<!-- resources/views/components/custom-datatable.blade.php -->
@props(['id', 'route', 'columns', 'search' => true, 'pageLength' => true, 'checkbox' => false, 'checkboxKey' => 'id'])


<div class="table-responsive">
    <table id="{{ $id }}" class="table table-sm table-striped">
        <thead>
            <tr>
                @if ($checkbox)
                    <th><input type="checkbox" id="selectAll-{{ $id }}" class="form-check-input dt-checkboxes"></th>
                @endif
                @foreach ($columns as $column)
                    <th>{{ $column['title'] ?? $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kirim konfigurasi dari Blade ke JS
            const options = {
                route: '{{ $route }}',
                checkbox: {{ $checkbox ? 'true' : 'false' }},
                checkboxKey: '{{ $checkboxKey }}',
                search: {{ $search ? 'true' : 'false' }},
                pageLength: {{ $pageLength ? 'true' : 'false' }},
                columns: @json($columns)
            };

            // Inisialisasi DataTable custom
            const dataTableInstance = new CustomDataTables('{{ $id }}', options);

            // Simpan instance ke window object untuk diakses oleh fungsi lain
            window['DT_{{ $id }}'] = dataTableInstance;
        });
    </script>
@endpush
