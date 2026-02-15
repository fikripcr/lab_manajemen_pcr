@extends('layouts.auth.app')

@section('content')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
        <!-- Reset Password Card -->
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-4">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
                        <img src="{{ asset('images/logo-apps.png') }}" class="img-fluid " style="height: 100px; " alt="Logo" />
                    </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-2">Reset Password 🔒</h4>
                <p class="mb-4">Create a new password for your account</p>

                <form method="POST" action="{{ route('password.store') }}" class="mb-3">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email', $request->email) }}" required autofocus />
                        @error('email')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="password">New Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                   placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                   aria-describedby="password" required />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                   placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                   aria-describedby="password_confirmation" required />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                    <x-tabler.button type="submit" class="w-100" text="Reset Password" />
                </form>
            </div>
        </div>
        <!-- /Reset Password Card -->
    </div>
</div>
@endsection

