@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Perkuliahan /</span> Mata Kuliah</h4>
        <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add New Mata Kuliah
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Mata Kuliah List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'mata-kuliah-table',
                'filters' => [
                    [
                        'id' => 'sksFilter',
                        'name' => 'sks',
                        'label' => 'SKS',
                        'type' => 'select',
                        'column' => 3, // SKS column index
                        'options' => [
                            '' => 'All SKS',
                            '1' => '1 SKS',
                            '2' => '2 SKS',
                            '3' => '3 SKS',
                            '4' => '4 SKS'
                        ],
                        'placeholder' => 'Select SKS'
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            @include('components.flash-message')

            <div class="table-responsive">
                <table id="mata-kuliah-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>SKS</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.sweetalert')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if DataTable is already initialized to avoid re-initialization
            if (!$.fn.DataTable.isDataTable('#mata-kuliah-table')) {
                var table = $('#mata-kuliah-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('mata-kuliah.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-mata-kuliah-table').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }
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
                            data: 'kode_mk',
                            name: 'kode_mk'
                        },
                        {
                            data: 'nama_mk',
                            name: 'nama_mk'
                        },
                        {
                            data: 'sks',
                            name: 'sks',
                            className: 'text-center'
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

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-mata-kuliah-table', function() {
                    table.search(this.value).draw();
                });
            }
        });
    </script>
@endpush
