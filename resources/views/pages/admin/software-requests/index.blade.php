@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0">Software Request Management</h4>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Software Request List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-admin.datatable-page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'software-requests-table'
            ])
        </div>
        <div class="card-body">
            <x-admin.flash-message />
            <div class="table-responsive">
                <table id="software-requests-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Software</th>
                            <th>Dosen</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Created At</th>
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
            if (!$.fn.DataTable.isDataTable('#software-requests-table')) {
                var table = $('#software-requests-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('software-requests.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-software-requests-table').val();
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
                            data: 'nama_software',
                            name: 'nama_software'
                        },
                        {
                            data: 'dosen.name',
                            name: 'dosen.name',
                            render: function(data, type, row) {
                                return data || 'Guest';
                            }
                        },
                        {
                            data: 'mata_kuliah',
                            name: 'mata_kuliah'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                return data ? moment(data).format('DD MMM YYYY') : '-';
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

                // Setup common DataTable behaviors
                setupCommonDataTableBehaviors(table, {
                    searchInputSelector: '#globalSearch-software-requests-table',
                    pageLengthSelector: '#pageLength'
                });
            }
        });
    </script>
@endpush
