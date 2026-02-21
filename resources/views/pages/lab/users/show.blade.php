@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.tabler.empty' : 'layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="{{ $user->name }}" pretitle="User Details">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.users.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-tabler.flash-message />

            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <a href="{{$user->avatar_url}}" target="_blank">
                                    <img src="{{ $user->avatar_medium_url }}" 
                                         alt="user-avatar" 
                                         class="rounded-circle border border-3 border-white shadow-lg" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </a>
                            </div>
                            
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            <div class="mb-3">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary-lt me-1">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary-lt">No roles assigned</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <x-tabler.button type="button" class="btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal" icon="ti ti-key" text="Change Password" />

                                @if(auth()->id() == $user->id)
                                    <x-tabler.button type="edit" :href="route('lab.users.edit', $user->encrypted_id)" text="Edit Profile" />
                                @else
                                    <x-tabler.button type="edit" :href="route('lab.users.edit', $user->encrypted_id)" text="Edit User" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-info-circle me-2"></i>Account Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Email Verification -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-blue-lt me-2">
                                                <i class="ti ti-certificate"></i>
                                            </div>
                                            <div class="text-muted small">Email Verification</div>
                                        </div>
                                        <div class="h4 mb-0">
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success fs-5">Verified</span>
                                                <div class="text-muted small mt-1">{{ $user->email_verified_at->format('M d, Y') }}</div>
                                            @else
                                                <span class="badge bg-warning fs-5">Not Verified</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Member Since -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-green-lt me-2">
                                                <i class="ti ti-calendar-plus"></i>
                                            </div>
                                            <div class="text-muted small">Member Since</div>
                                        </div>
                                        <div class="h4 mb-0">{{ $user->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <!-- Last Updated -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-purple-lt me-2">
                                                <i class="ti ti-refresh"></i>
                                            </div>
                                            <div class="text-muted small">Last Updated</div>
                                        </div>
                                        <div class="h4 mb-0">{{ $user->updated_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <!-- Last Login -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-cyan-lt me-2">
                                                <i class="ti ti-login"></i>
                                            </div>
                                            <div class="text-muted small">Last Login</div>
                                        </div>
                                        @if($user->last_login_at)
                                            <div class="h4 mb-0">{{ $user->last_login_at->format('M d, Y H:i') }}</div>
                                            <div class="text-muted small">{{ $user->last_login_at->diffForHumans() }}</div>
                                        @else
                                            <div class="h4 mb-0 text-muted">Never</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Account Expiration -->
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-orange-lt me-2">
                                                <i class="ti ti-hourglass-empty"></i>
                                            </div>
                                            <div class="text-muted small">Account Expiration</div>
                                        </div>
                                        <div class="h4 mb-0">
                                            @if($user->expired_at)
                                                @if($user->expired_at->isFuture())
                                                    <span class="badge bg-info fs-5">
                                                        Expires {{ $user->expired_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-muted ms-2">({{ $user->expired_at->diffForHumans() }})</span>
                                                @else
                                                    <span class="badge bg-danger fs-5">
                                                        Expired on {{ $user->expired_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-danger ms-2">({{ $user->expired_at->diffForHumans() }})</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success fs-5">No Expiration</span>
                                                <span class="text-muted ms-2">Account never expires</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <x-tabler.form-modal
        id="changePasswordModal"
        title="Change Password"
        route="{{ route('password.update') }}"
        method="PUT"
        submitText="Change Password"
    >
        <x-tabler.form-input 
            type="password" 
            name="current_password" 
            label="Current Password" 
            required 
            autocomplete="current-password" 
        />

        <x-tabler.form-input 
            type="password" 
            name="password" 
            label="New Password" 
            required 
            autocomplete="new-password" 
        />

        <x-tabler.form-input 
            type="password" 
            name="password_confirmation" 
            label="Confirm New Password" 
            required 
            autocomplete="new-password" 
        />
    </x-tabler.form-modal>
@endsection
