@extends('layouts.admin.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Profile</h4>

    <div class="row">
        <div class="col-md-12">
            @include('components.flash-message')

            <ul class="nav nav-pills nav-fill mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#nav-profile">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#nav-password">Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#nav-security">Security</a>
                </li>
            </ul>

            <div class="tab-content p-0">
                <div class="tab-pane fade show active" id="nav-profile">
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Information</h5>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="email">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">
                                            <i class='bx bx-save me-1'></i> Update Profile
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-password">
                    <div class="card mb-4">
                        <h5 class="card-header">Change Password</h5>
                        <div class="card-body">
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="current_password">Current Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" 
                                               autocomplete="current-password">
                                        @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="password">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" 
                                               autocomplete="new-password">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" 
                                               autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">
                                            <i class='bx bx-lock-alt me-1'></i> Change Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-security">
                    <div class="card mb-4">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                            <div class="mb-3 col-12 col-md-12 mb-4">
                                <h5>Are you sure you want to delete your account?</h5>
                                <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                            </div>
                            <form id="delete-user-form" method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="delete-password">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="delete-password" name="password" 
                                               placeholder="Enter your password">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button class="btn btn-danger" type="submit">
                                            <i class='bx bx-trash me-1'></i> Delete Account
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection