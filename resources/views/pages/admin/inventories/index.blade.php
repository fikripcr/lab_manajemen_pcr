@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Inventory Management</h4>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-2">
            <h5 class="mb-2 mb-sm-0">Inventaris List</h5>
            <div class="d-flex flex-wrap gap-2">
                <div class="me-3 mb-2 mb-sm-0">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search inventories...">
                </div>
                <div class="me-3 mb-2 mb-sm-0">
                    <select id="pageLength" class="form-select form-select-sm">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <a href="{{ route('inventories.create') }}" class="btn btn-primary btn-sm mb-2 mb-sm-0">
                    <i class="bx bx-plus"></i> Add New Inventory
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('components.flash-message')
            <div class="table-responsive">
                <table id="inventaris-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
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
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#inventaris-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('inventories.dataTable') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
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
                    { data: 'kondisi_terakhir', name: 'kondisi_terakhir' },
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
                            return data ? moment(data).format('MMM DD, YYYY') : '-';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                responsive: true,
                dom: 'rtip' // Only show table, info, and paging - hide default search and length inputs
            });

            // Handle search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Handle page length change
            $('#pageLength').on('change', function() {
                var pageLength = parseInt($(this).val());
                table.page.len(pageLength).draw();
            });
        });
    </script>
@endpush
