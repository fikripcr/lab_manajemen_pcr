@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Inventory Management" pretitle="Inventory">
    <x-slot:actions>
        <x-sys.button type="export" id="exportBtn" text="Export Excel" />
        <x-sys.button type="create" :href="route('inventaris.create')" text="Create" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="inventaris-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="inventaris-table" />
                </div>
                <div>
                    <x-sys.datatable-filter dataTableId="inventaris-table">
                        <div style="min-width: 150px;">
                            <select name="condition" id="conditionFilter" class="form-select">
                                <option value="">All Conditions</option>
                                <option value="Baik">Good</option>
                                <option value="Rusak Ringan">Minor Damage</option>
                                <option value="Rusak Berat">Major Damage</option>
                                <option value="Tidak Dapat Digunakan">Cannot Be Used</option>
                            </select>
                        </div>
                    </x-sys.datatable-filter>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <x-admin.flash-message />
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Equipment Name',
                        'data' => 'nama_alat',
                        'name' => 'nama_alat',
                    ],
                    [
                        'title' => 'Type',
                        'data' => 'jenis_alat',
                        'name' => 'jenis_alat',
                    ],
                    [
                        'title' => 'Condition',
                        'data' => 'kondisi_terakhir',
                        'name' => 'kondisi_terakhir'
                    ],
                    [
                        'title' => 'Last Check',
                        'data' => 'tanggal_pengecekan',
                        'name' => 'tanggal_pengecekan',
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-sys.datatable id="inventaris-table" :route="route('inventaris.data')" :columns="$columns" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle export button click
            $('#exportBtn').on('click', function() {
                var searchValue = $('#inventaris-table-search').val();
                var conditionValue = $('#conditionFilter').val();

                var params = new URLSearchParams();
                if (searchValue) params.append('search', searchValue);
                if (conditionValue) params.append('condition', conditionValue);

                window.location.href = '{{ route('inventaris.export') }}?' + params.toString();
            });
        });
    </script>
@endpush
