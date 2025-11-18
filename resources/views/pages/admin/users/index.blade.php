@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> User Management</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary me-2">
                <i class="bx bx-plus"></i> Add New User
            </a>
            <a href="{{ route('users.import.show') }}" class="btn btn-info me-2">
                <i class="bx bx-import"></i> Import Users
            </a>

            <!-- Dropdown for Export -->
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('users.export') }}">
                            <i class='bx bx-file me-1'></i> Export to Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.export.pdf', ['type' => 'summary']) }}">
                            <i class='bx bx-file me-1'></i> Export to PDF
                        </a>
                    </li>
                </ul>
            </div>
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

            <x:datatable.datatable
                id="users-table"
                route="{{ route('users.data') }}"
                :columns="[
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Name',
                        'data' => 'name',
                        'name' => 'name'
                    ],
                    [
                        'title' => 'Email',
                        'data' => 'email',
                        'name' => 'email'
                    ],
                    [
                        'title' => 'Role',
                        'data' => 'roles',
                        'name' => 'roles'
                    ],
                    [
                        'title' => 'ID',
                        'data' => null,
                        'name' => 'id',
                        'orderable' => false,
                        'searchable' => false,
                        'render' => 'function(data, type, row) {
                            return row.nim || row.nip || \'-\';
                        }'
                    ],
                    [
                        'title' => 'Expiration',
                        'data' => 'expired_at',
                        'name' => 'expired_at'
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false
                    ]
                ]"
                search="true"
                page-length-selector="#pageLength"
            />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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

        // Handle export button click
        $(document).on('click', '#exportBtn', function() {
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
    </script>
    @include('components.sweetalert')
@endpush
