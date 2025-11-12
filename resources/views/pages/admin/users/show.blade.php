@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">User Details /</span> {{ $user->name }}
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2">
                            <i class='bx bx-edit me-1'></i> Edit
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this user? All their data will be permanently removed.')">
                                <i class='bx bx-trash me-1'></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                             alt="user-avatar" class="d-block rounded-circle w-px-100 h-px-100" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="mb-1">{{ $user->email }}</p>
                            <span class="badge bg-label-primary me-1">{{ $user->roles->first()?->name ?? 'No Role' }}</span>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-2">Personal Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role</strong></td>
                                    <td><span class="badge bg-label-primary">{{ $user->roles->first()?->name ?? 'No Role' }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>NPM</strong></td>
                                    <td>{{ $user->npm ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIP</strong></td>
                                    <td>{{ $user->nip ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-2">Account Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Created At</strong></td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated At</strong></td>
                                    <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Verified</strong></td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class='bx bx-arrow-back me-1'></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
