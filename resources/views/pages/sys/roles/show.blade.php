@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0">Role Details: {{ $role->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('sys.roles.edit', $role) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit Role
            </a>
            <a href="{{ route('sys.roles.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-sys.flash-message />

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Role Name:</h6>
                            <p class="mb-0">{{ $role->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Users Assigned:</h6>
                            <p class="mb-0">{{ $role->users->count() }} users</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Assigned Permissions:</h6>
                        @if($role->permissions->count() > 0)
                            <div class="permissions-list">
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-label-primary me-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No permissions assigned to this role.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Assigned Users:</h6>
                        @if($role->users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($role->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach($user->roles as $userRole)
                                                        <span class="badge bg-label-info me-1">{{ $userRole->name }}</span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">No users are assigned to this role.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Function to toggle select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.permission-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Function to handle form submission with SweetAlert
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('savePermissionsBtn');
            const originalText = submitBtn.innerHTML;

            // Disable the button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Saving...';

            const formData = new FormData(form);

            axios.post(form.action, formData, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT' // Laravel needs this for PUT via form
                }
            })
            .then(function(response) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (response.data.success) {
                    showSuccessMessage('Success!', response.data.message);
                } else {
                    showErrorMessage('Error!', response.data.message);
                }
            })
            .catch(function(error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (error.response && error.response.data && error.response.data.message) {
                    showErrorMessage('Error!', error.response.data.message);
                } else {
                    showErrorMessage('Error!', 'An error occurred while saving permissions.');
                }
            });
        });

        // Update select all checkbox state when individual checkboxes are checked/unchecked
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.permission-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');

                const selectAllCheckbox = document.getElementById('selectAll');
                selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
            });
        });
    </script>
@endsection
