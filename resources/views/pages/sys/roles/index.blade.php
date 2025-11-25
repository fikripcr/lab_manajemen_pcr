@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Access Control /</span> Role</h4>
        <a href="{{ route('sys.roles.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Role
        </a>
    </div>
<x-sys.flash-message />
    <div class="row">
        @forelse($roles as $role)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">{{ $role->name }}</h5>
                            <p class="text-muted mb-2">{{ $role->users_count }} users assigned</p>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('sys.roles.show', $role) }}">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <a class="dropdown-item" href="{{ route('sys.roles.edit', $role) }}">
                                    <i class="bx bx-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('sys.roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this role?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('sys.roles.destroy', $role->encryptedId) . '\')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="card-text mb-1"><strong>Assigned Permissions:</strong></p>
                        @if($role->permissions->count() > 0)
                            <div class="permissions-list">
                                @foreach($role->permissions->take(5) as $permission)
                                    <span class="badge bg-label-primary me-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 5)
                                    <span class="badge bg-label-secondary me-1 mb-1">+{{ $role->permissions->count() - 5 }} more</span>
                                @endif
                            </div>
                        @else
                            <p class="text-muted mb-0">No permissions assigned</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bx bx-shield bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">No roles found</h5>
                <p class="text-muted">Get started by creating a new role</p>
                <a href="{{ route('sys.roles.create') }}" class="btn btn-primary">Create Role</a>
            </div>
        </div>
        @endforelse
    </div>

@endsection
