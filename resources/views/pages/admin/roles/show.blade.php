@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Role Details: {{ $role->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit Role
            </a>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Roles
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    @include('components.flash-message')

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

                    <!-- Permissions Management Section -->
                    <div class="mt-4">
                        <h5 class="mb-3">Manage Permissions</h5>
                        <form id="permissionForm" method="POST" action="{{ route('roles.update-permissions', $role) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Select Permissions for this Role:</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                            <label class="form-check-label" for="selectAll">Select All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                @foreach($role->permissions as $permission)
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" checked>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @php $existingPermissionNames = $role->permissions->pluck('name')->toArray(); @endphp
                                @foreach($allPermissions as $permission)
                                    @if(!in_array($permission->name, $existingPermissionNames))
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}">
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" id="savePermissionsBtn">
                                    <i class="bx bx-save me-1"></i> Save Permissions
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Role Deletion Section -->
                    <div class="mt-4 pt-4 border-top">
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this role? This will affect {{ $role->users->count() }} assigned users.')">
                                <i class="bx bx-trash me-1"></i> Delete Role
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('components.sweetalert')
    
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
            
            // Submit form using AJAX
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT'); // Laravel needs this for PUT via form
            
            const params = new URLSearchParams(formData).toString();
            
            xhr.onload = function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showSuccessMessage('Success!', response.message);
                    } else {
                        showErrorMessage('Error!', response.message);
                    }
                } else {
                    showErrorMessage('Error!', 'An error occurred while saving permissions.');
                }
            };
            
            xhr.onerror = function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                showErrorMessage('Error!', 'An error occurred while saving permissions.');
            };
            
            xhr.send(params);
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