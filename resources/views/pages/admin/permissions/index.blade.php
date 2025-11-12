@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Permission Management</h4>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Permission
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('components.flash-message')

                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Roles Assigned</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($permissions as $permission)
                                <tr>
                                    <td><strong>{{ $permission->name }}</strong></td>
                                    <td>{{ $permission->roles_count }} roles</td>
                                    <td>{{ $permission->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('permissions.show', $permission) }}" class="text-primary dropdown-item me-1" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('permissions.edit', $permission) }}" class="text-primary dropdown-item me-1" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm('Are you sure you want to delete this permission?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                            <i class="bx bx-key bx-lg text-muted mb-3"></i>
                                            <h5 class="mb-1">No permissions found</h5>
                                            <p class="text-muted">Get started by creating a new permission.</p>
                                            <a href="{{ route('permissions.create') }}" class="btn btn-primary mt-2">
                                                <i class="bx bx-plus me-1"></i> Create Permission
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($permissions->hasPages())
                    <div class="mt-4">
                        {{ $permissions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection