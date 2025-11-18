@extends('layouts.admin.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">User Details /</span> {{ $user->name }}
        </h4>

        <div class="row">
            <div class="col-md-12">
                <x-flash-message />

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                            @php
                                $avatarMedia = $user->getFirstMedia('avatar');
                            @endphp
                            @if($avatarMedia)
                                @php
                                    $avatarUrl = $avatarMedia->hasGeneratedConversion('avatar_small')
                                        ? $avatarMedia->getFullUrl('avatar_small')
                                        : $avatarMedia->getFullUrl();
                                @endphp
                                <img src="{{ $avatarUrl }}"
                                     alt="user-avatar" class="d-block rounded-circle w-px-100 h-px-100">
                            @else
                                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                                     alt="user-avatar" class="d-block rounded-circle w-px-100 h-px-100">
                            @endif

                            <div class="button-wrapper">
                                <h4 class="mb-0">{{ $user->name }}</h4>
                                <p class="mb-0 text-muted">{{ $user->email }}</p>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-label-primary me-1">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-label-secondary">No roles assigned</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="fullName">Full Name</label>
                                <input class="form-control" type="text" id="fullName"
                                       value="{{ $user->name }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="text" id="email"
                                       value="{{ $user->email }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="nim">NIM</label>
                                <input class="form-control" type="text" id="nim"
                                       value="{{ $user->nim ?? '-' }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="nip">NIP</label>
                                <input class="form-control" type="text" id="nip"
                                       value="{{ $user->nip ?? '-' }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="emailVerified">Email Verified</label>
                                <input class="form-control" type="text" id="emailVerified"
                                       value="{{ $user->email_verified_at ? 'Yes' : 'No' }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="memberSince">Member Since</label>
                                <input class="form-control" type="text" id="memberSince"
                                       value="{{ $user->created_at->format('M d, Y') }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="lastUpdate">Last Updated</label>
                                <input class="form-control" type="text" id="lastUpdate"
                                       value="{{ $user->updated_at->format('M d, Y') }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="lastLogin">Last Login</label>
                                <input class="form-control" type="text" id="lastLogin"
                                       value="{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="expiredAt">Account Expiration</label>
                                <input class="form-control" type="text" id="expiredAt"
                                       value="{{ $user->expired_at ? $user->expired_at->format('M d, Y') : 'No Expiration' }}" readonly>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class='bx bx-key me-1'></i> Change Password
                            </button>

                            @if(Auth::id() == decryptId($user->id))
                                <a href="{{ route('users.edit', $user->encrypted_id) }}" class="btn btn-primary me-2">
                                    <i class='bx bx-edit me-1'></i> Edit Profile
                                </a>
                            @else
                                <a href="{{ route('users.edit', $user->encrypted_id) }}" class="btn btn-primary me-2">
                                    <i class='bx bx-edit me-1'></i> Edit User
                                </a>
                            @endif

                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back me-1'></i> Back to Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="current_password">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password"
                                       autocomplete="current-password">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       autocomplete="new-password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation"
                                       autocomplete="new-password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account Modal -->
        @if(Auth::id() === $user->id)
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-body">
                            <div class="mb-3">
                                <h5 class="text-danger">Are you absolutely sure?</h5>
                                <p>All of your data will be permanently removed from our servers forever. This action cannot be undone.</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="delete_password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="delete_password" name="password"
                                       placeholder="Enter your password to confirm">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
