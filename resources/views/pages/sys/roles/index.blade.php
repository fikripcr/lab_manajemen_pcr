@extends('layouts.sys.app')

@section('title', 'Roles')

@section('header')
<x-sys.page-header title="Roles" pretitle="Access Control">
    <x-slot:actions>
        <x-sys.button type="create" :modal-url="route('sys.roles.create')" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')

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
                    <div class="d-flex align-items-center">
                        <x-sys.datatables-actions
                            :view-url="route('sys.roles.show', $role)"
                            :edit-url="route('sys.roles.edit', $role)"
                            :delete-url="route('sys.roles.destroy', $role)"
                        />
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
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
            <h5 class="text-muted">No roles found</h5>
            <p class="text-muted">Get started by creating a new role</p>
            <a href="#" class="btn btn-primary ajax-modal-btn" data-url="{{ route('sys.roles.create') }}" data-modal-title="Create New Role">Create Role</a>
        </div>
    </div>
    @endforelse
</div>
@endsection
