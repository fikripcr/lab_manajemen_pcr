@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> Inventory Management</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('inventories.create') }}" class="btn btn-primary me-2">
                <i class="bx bx-plus"></i> Add New Inventory
            </a>
            <button type="button" class="btn btn-success" id="exportBtn">
                <i class="bx bx-export"></i> Export Excel
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Inventaris List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'inventaris-table',
                'filters' => [
                    [
                        'id' => 'conditionFilter',
                        'name' => 'condition',
                        'label' => 'Condition',
                        'type' => 'select',
                        'column' => '',
                        'options' => [
                            '' => 'All Conditions',
                            'Baik' => 'Good',
                            'Rusak Ringan' => 'Minor Damage',
                            'Rusak Berat' => 'Major Damage',
                            'Tidak Dapat Digunakan' => 'Cannot Be Used'
                        ],
                        'placeholder' => 'Select Condition'
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            @include('components.flash-message')
            <div class="table-responsive">
                <table id="inventaris-table" class="table  " style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipment Name</th>
                            <th>Type</th>
                            <th>Condition</th>
                            <th>Lab</th>
                            <th>Last Check</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#inventaris-table')) {
                var table = $('#inventaris-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('inventories.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-inventaris-table').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }

                            // Add condition filter to the request
                            d.condition = $('#conditionFilter').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'nama_alat',
                            name: 'nama_alat',
                            render: function(data, type, row) {
                                return '<span class="fw-medium">' + data + '</span>';
                            }
                        },
                        {
                            data: 'jenis_alat',
                            name: 'jenis_alat',
                            render: function(data, type, row) {
                                return '<span class="badge bg-label-info me-1">' + data + '</span>';
                            }
                        },
                        {
                            data: 'kondisi_terakhir',
                            name: 'kondisi_terakhir'
                        },
                        {
                            data: null,
                            name: 'lab.name',
                            render: function(data, type, row) {
                                return row.lab ? row.lab.name : '-';
                            }
                        },
                        {
                            data: 'tanggal_pengecekan',
                            name: 'tanggal_pengecekan',
                            render: function(data) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    dom: 'rtip' // Only show table, info, and paging
                });

                // Handle condition filter change
                $(document).on('change', '#conditionFilter', function() {
                    table.ajax.reload();
                });

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-inventaris-table', function() {
                    table.search(this.value).draw();
                });

                // Handle export button click
                $('#exportBtn').on('click', function() {
                    // Get current search and filter values from the search filter component
                    var searchValue = $('#globalSearch-inventaris-table').val();
                    var conditionValue = $('#conditionFilter').val();

                    // Build query parameters
                    var params = new URLSearchParams();
                    if(searchValue) {
                        params.append('search', searchValue);
                    }
                    if(conditionValue) {
                        params.append('condition', conditionValue);
                    }

                    // Redirect to export URL with parameters
                    window.location.href = '{{ route('inventories.export') }}?' + params.toString();
                });
            }
        });
    </script>
    @include('components.sweetalert')
@endpush>
