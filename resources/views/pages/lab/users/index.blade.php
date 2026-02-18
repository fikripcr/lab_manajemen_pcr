@extends('layouts.tabler.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                User Management
            </h2>
            <div class="text-muted mt-1">Tables / User Management</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button href="{{ route('lab.users.import.show') }}" icon="ti ti-file-import" text="Import" class="btn-secondary d-none d-sm-inline-block" />
                <x-tabler.button href="{{ route('lab.users.export') }}" icon="ti ti-file-export" text="Export" class="btn-secondary d-none d-sm-inline-block" />
                <x-tabler.button href="{{ route('lab.users.create') }}" icon="ti ti-plus" text="Create" class="btn-primary d-none d-sm-inline-block" />
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'users-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'users-table'" />
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="d-flex flex-wrap gap-2">

                    <!-- Action buttons for selected users -->
                    <div id="bulk-actions-users-table" class="d-none">
                        <x-tabler.button type="button" class="btn-sm btn-primary" onclick="bulkAction('send-notification')" icon="bx bx-envelope" text="Send Notification" />
                        <x-tabler.button type="button" class="btn-sm btn-danger" onclick="bulkAction('delete')" icon="bx bx-trash" text="Delete" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="users-table" route="{{ route('lab.users.data') }}" :columns="[
                [
                    'title' => 'Name',
                    'data' => 'name',
                    'name' => 'name',
                ],
                [
                    'title' => 'Email',
                    'data' => 'email',
                    'name' => 'email',
                ],
                [
                    'title' => 'Role',
                    'data' => 'roles',
                    'name' => 'roles',
                ],
                [
                    'title' => 'Expiration',
                    'data' => 'expired_at',
                    'name' => 'expired_at',
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" with-checkbox="true" checkbox-key="id"/>
        </div>
    </div>
@endsection

@push('scripts')
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

            switch (action) {
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
@endpush
