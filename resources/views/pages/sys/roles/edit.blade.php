@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Edit Role: {{ $role->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('roles.show', $role) }}" class="btn btn-info">
                <i class="bx bx-show"></i> View Role
            </a>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Roles
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-flash-message />

                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $role->name) }}" >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label">Permissions</label>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                        <i class="bx bx-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="deselectAllBtn">
                                        <i class="bx bx-square"></i> Deselect All
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                @forelse($permissions as $permission)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox @error('permissions') is-invalid @enderror"
                                                   type="checkbox"
                                                   value="{{ $permission->name }}"
                                                   id="perm_{{ $permission->id }}"
                                                   name="permissions[]"
                                                   {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted">No permissions available. <a href="{{ route('permissions.index') }}">Create permissions first</a>.</p>
                                    </div>
                                @endforelse
                            </div>
                            @error('permissions')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const selectAllBtn = document.getElementById('selectAllBtn');
                                const deselectAllBtn = document.getElementById('deselectAllBtn');
                                const checkboxes = document.querySelectorAll('.permission-checkbox');

                                selectAllBtn.addEventListener('click', function() {
                                    checkboxes.forEach(checkbox => checkbox.checked = true);
                                });

                                deselectAllBtn.addEventListener('click', function() {
                                    checkboxes.forEach(checkbox => checkbox.checked = false);
                                });
                            });
                        </script>
                        @endpush

                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update Role
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
