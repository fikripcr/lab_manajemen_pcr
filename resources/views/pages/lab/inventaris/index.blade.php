@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Inventaris Lab" pretitle="Aset & Peralatan">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.inventaris.create')" modal-title="Tambah Inventaris" />
        <x-tabler.button type="button" icon="ti ti-download" text="Export" class="btn-outline-primary" id="btn-export" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="inventaris-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="inventaris-table" />
                </div>
                <div>
                    <x-tabler.datatable-filter dataTableId="inventaris-table">
                        <div style="min-width: 150px;">
                            <x-tabler.form-select name="condition" id="conditionFilter" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" placeholder="All Conditions" class="mb-0" />
                        </div>
                    </x-tabler.datatable-filter>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <x-tabler.flash-message />
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
            <x-tabler.datatable id="inventaris-table" :route="route('lab.inventaris.data')" :columns="$columns" />
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

                window.location.href = '{{ route('lab.inventaris.export') }}?' + params.toString();
            });
        });
    </script>
@endpush
