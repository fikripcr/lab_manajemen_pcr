@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> User Management</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary me-2">
                <i class="bx bx-plus"></i> Add New User
            </a>
            <a href="{{ route('users.import.show') }}" class="btn btn-info me-2">
                <i class="bx bx-import"></i> Import Users
            </a>

            <!-- Dropdown for Export -->
            <a href="{{ route('users.export') }}" class="btn btn-success me-2">
                <i class='bx bx-export'></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">User List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-admin.datatable-page-length id="pageLength" selected="10" />
                    </div>
                    <!-- Action buttons for selected users -->
                    <div id="bulk-actions-users-table" class="d-none">
                        <button type="button" class="btn btn-sm btn-primary" onclick="bulkAction('send-notification')">
                            <i class="bx bx-envelope"></i> Send Notification
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            <x-admin.datatable-search-filter :dataTableId="'users-table'" />
        </div>
        <div class="card-body">
            <x-admin.flash-message />

            <x-admin.datatable
                id="users-table"
                route="{{ route('users.data') }}"
                :columns="[
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
                with-checkbox="true"
                checkbox-key="id"
                search="true"
                page-length-selector="#pageLength"
            />
        </div>
    </div>
    <script>
        // Function to show/hide bulk action buttons based on whether any rows are selected
        function updateBulkActionVisibility() {
            const selectedCount = window.getSelectedIds ? window.getSelectedIds().length : 0;
            const bulkActionsDiv = document.getElementById('bulk-actions-users-table');

            if (selectedCount > 0) {
                bulkActionsDiv.classList.remove('d-none');
            } else {
                bulkActionsDiv.classList.add('d-none');
            }
        }

        // Add event listeners to handle checkbox changes
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for changes to checkboxes in the table
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('select-row') || e.target.id.includes('selectAll')) {
                    setTimeout(updateBulkActionVisibility, 100); // Delay to ensure state is updated
                }
            });
        });

        // Function to handle bulk actions
        function bulkAction(action) {
            const selectedIds = window.getSelectedIds();

            if (selectedIds.length === 0) {
                showWarningMessage('Warning', 'Please select at least one user to perform this action');
                return;
            }

            let actionText = '';
            let actionColor = 'primary';

            switch(action) {
                case 'send-notification':
                    actionText = 'Send Notification';
                    actionColor = 'primary';
                    break;
                case 'impersonate':
                    actionText = 'Login As';
                    actionColor = 'info';
                    break;
                case 'delete':
                    actionText = 'Delete';
                    actionColor = 'danger';
                    break;
            }

            showBulkActionConfirmation(actionText, selectedIds.length, 'user').then((result) => {
                if (result.isConfirmed) {
                    // In a real implementation, you would send the selectedIds to a backend endpoint
                    // For now, just show a confirmation message
                    showSuccessMessage(`${actionText} action performed on ${selectedIds.length} user(s)`).then(() => {
                        // Deselect all checkboxes after action
                        const selectAllCheckbox = document.getElementById('selectAll-users-table');
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                        }
                        // Also uncheck individual checkboxes
                        document.querySelectorAll('.select-row').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        updateBulkActionVisibility();
                    });
                }
            });
        }
    </script>
@endsection

@push('scripts')
    <script>

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
    <script>
        // Add additional functionality after the DataTable initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for DataTable to be initialized
            setTimeout(function() {
                if ($('#users-table').DataTable()) {
                    // Update visibility when table is redrawn (pagination, sorting, etc.)
                    $('#users-table').on('draw.dt', function() {
                        setTimeout(updateBulkActionVisibility, 100);
                    });
                }
            }, 500); // Slight delay to ensure DataTable is initialized
        });
    </script>
@endpush
