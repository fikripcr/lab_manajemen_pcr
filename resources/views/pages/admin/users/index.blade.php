@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> User Management</h4>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-2">
            <h5 class="mb-2 mb-sm-0">User List</h5>
            <div class="d-flex flex-wrap gap-2">
                <div class="me-3 mb-2 mb-sm-0">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search users...">
                </div>
                <div class="me-3 mb-2 mb-sm-0">
                    <select id="pageLength" class="form-select form-select-sm">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm mb-2 mb-sm-0">
                    <i class="bx bx-plus"></i> Add New User
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('components.flash-message')
            <div class="table-responsive">
                <table id="users-table" class="table " style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>ID</th>
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
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{{ route('users.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="` + (row.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&color=7F9CF5&background=EBF4FF') + `"
                                             alt="` + row.name + `" class="rounded-circle w-px-40 h-40">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-nowrap">` + row.name + `</span>
                                        <small class="text-muted">` + row.created_at + `</small>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: null,
                        name: 'id',
                        render: function(data, type, row) {
                            return row.npm || row.nip || '-';
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
