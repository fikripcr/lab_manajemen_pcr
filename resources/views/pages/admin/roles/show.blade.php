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

                    <div class="mt-4">
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
@endsection