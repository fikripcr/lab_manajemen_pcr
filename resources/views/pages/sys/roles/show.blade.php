@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Role Details: {{ $role->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted text-uppercase small fw-bold">Role Name</h6>
                <p class="mb-0 fs-3">{{ $role->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted text-uppercase small fw-bold">Users Assigned</h6>
                <div class="d-flex align-items-center">
                    <span class="badge bg-blue-lt">{{ $role->users->count() }} users</span>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Assigned Permissions</h6>
            @if($role->permissions->count() > 0)
                <div class="border rounded p-2 bg-light-lt" style="max-height: 200px; overflow-y: auto;">
                    @foreach($role->permissions as $permission)
                        <span class="badge bg-label-secondary me-1 mb-1">{{ $permission->name }}</span>
                    @endforeach
                </div>
            @else
                <div class="text-muted small fst-italic border rounded p-2 bg-light-lt">No permissions assigned to this role.</div>
            @endif
        </div>

        <div class="mb-3">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Assigned Users</h6>
            @if($role->users->count() > 0)
                <div class="table-responsive border rounded" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-sm table-vcenter mb-0">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td><small class="text-muted">{{ $user->email }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted small fst-italic border rounded p-2 bg-light-lt">No users are assigned to this role.</div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
        <x-tabler.button :href="route('sys.roles.edit', $role)" class="btn-primary" icon="ti ti-pencil" text="Edit Role" />
    </div>
@else
    @extends('layouts.sys.app')

    @section('title', 'Details Role')

    @section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Access Control</div>
            <h2 class="page-title">Role Details: {{ $role->name }}</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('sys.roles.edit', $role) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                    Edit Role
                </a>
                <a href="{{ route('sys.roles.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                    Back
                </a>
            </div>
        </div>
    </div>
    @endsection

    @section('content')
        <div class="card-body">
            <x-tabler.flash-message />

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
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-users-{{ $role->id }}"
                            :columns="[
                                ['name' => 'User Name'],
                                ['name' => 'Email'],
                                ['name' => 'Role']
                            ]"
                        >
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
                        </x-tabler.datatable-client>
                    </div>
                @else
                    <p class="text-muted mb-0">No users are assigned to this role.</p>
                @endif
            </div>
        </div>
    </div>
    @endsection
@endif
