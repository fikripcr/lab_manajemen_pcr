@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Permission Details: {{ $permission->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit
            </a>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-body">
                    @include('components.flash-message')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Permission Name:</h6>
                            <p class="mb-0"><strong>{{ $permission->name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Created At:</h6>
                            <p class="mb-0">{{ $permission->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Updated At:</h6>
                            <p class="mb-0">{{ $permission->updated_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Roles Assigned:</h6>
                            <p class="mb-0">
                                @if($permission->roles->count() > 0)
                                    <span class="badge bg-label-info">{{ $permission->roles->count() }} roles</span>
                                @else
                                    <span class="badge bg-label-secondary">No roles</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($permission->roles->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted">Assigned to Roles:</h6>
                        <div class="row">
                            @foreach($permission->roles as $role)
                                <div class="col-md-6 mb-2">
                                    <span class="badge bg-label-primary">{{ $role->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this permission? This will remove it from {{ $permission->roles->count() }} roles.')">
                                <i class="bx bx-trash me-1"></i> Delete Permission
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection