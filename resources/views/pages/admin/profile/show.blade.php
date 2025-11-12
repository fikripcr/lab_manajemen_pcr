@extends('layouts.admin.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center justify-content-between gap-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                                 alt="user-avatar" class="d-block rounded-circle w-px-100 h-px-100">
                            <div class="button-wrapper">
                                <h4 class="mb-1">{{ $user->name }}</h4>
                                <p class="mb-1">{{ $user->email }}</p>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                    <span class="badge bg-label-primary me-1">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-label-secondary">No roles assigned</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class='bx bx-edit me-1'></i> Edit Profile
                        </a>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="firstName">Full Name</label>
                            <input class="form-control" type="text" id="firstName" value="{{ $user->name }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" type="text" id="email" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="npm">NPM</label>
                            <input class="form-control" type="text" id="npm" value="{{ $user->npm ?? '-' }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="nip">NIP</label>
                            <input class="form-control" type="text" id="nip" value="{{ $user->nip ?? '-' }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">Email Verified</label>
                            <input class="form-control" type="text" id="status" 
                                   value="{{ $user->email_verified_at ? 'Yes' : 'No' }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="joinDate">Member Since</label>
                            <input class="form-control" type="text" id="joinDate" 
                                   value="{{ $user->created_at->format('M d, Y') }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="lastUpdate">Last Updated</label>
                            <input class="form-control" type="text" id="lastUpdate" 
                                   value="{{ $user->updated_at->format('M d, Y') }}" disabled>
                        </div>
                    </div>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
</div>
@endsection