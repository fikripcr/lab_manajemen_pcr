@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> User Management</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary me-2">
                <i class="bx bx-plus"></i> Add New User
            </a>
            <a href="{{ route('users.import') }}" class="btn btn-info me-2">
                <i class="bx bx-import"></i> Import Users
            </a>
            <button type="button" class="btn btn-success" id="exportBtn">
                <i class="bx bx-export"></i> Export Excel
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">User List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'users-table'
            ])
        </div>
        <div class="card-body">
            <x-flash-message />
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
        document.addEventListener('DOMContentLoaded', function() {
            if (!$.fn.DataTable.isDataTable('#users-table')) {
                var table = $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('users.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-users-table').val();
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
                            data: 'name',
                            name: 'name',
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
                                return row.nim || row.nip || '-';
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

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-users-table', function() {
                    table.search(this.value).draw();
                });

                // Handle export button click
                $('#exportBtn').on('click', function() {
                    // Get current search value from the search filter component
                    var searchValue = $('#globalSearch-users-table').val();

                    // Build query parameters
                    var params = new URLSearchParams();
                    if(searchValue) {
                        params.append('search', searchValue);
                    }

                    // Redirect to export URL with parameters
                    window.location.href = '{{ route('users.export') }}?' + params.toString();
                });
            }
        });

        // Function to send notification to a specific user
        function sendNotificationToUser(url, userName) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal mengirim notifikasi ke ' + userName,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim notifikasi',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }

        // Function to login as a specific user
        function loginAsUser(url, userName) {
            Swal.fire({
                title: 'Konfirmasi Login As',
                text: 'Apakah Anda yakin ingin login sebagai ' + userName + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, login sebagai dia',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Redirect to dashboard
                                window.location.href = data.redirect;
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal login sebagai ' + userName,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat login sebagai user',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }
    </script>
    @include('components.sweetalert')
@endpush
