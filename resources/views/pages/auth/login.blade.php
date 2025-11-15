@extends('layouts.auth.app')

@section('content')
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Login Basic Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <img src="{{ asset('digilab-crop.png') }}" class="img-fluid " style="height: 100px; " alt="Logo" />
                        </a>
                    </div>
                    <h4 class="mb-2">Welcome to {{ config('app.name', 'Sneat') }}! 👋</h4>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>
                    <!-- /Logo -->
                    <x-flash-message />

                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Username</label>
                            <input type="text" class="form-control" value="user1@contoh-lab.ac.id" id="email" name="email" placeholder="Enter your email or username" value="{{ old('email') }}" />
                            @error('email')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        <small>Forgot Password?</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" value="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>

                    <div class="d-flex flex-column align-items-center mb-3">
                        <div class="mb-3 w-100">
                            <a href="{{ route('auth.google') }}" class="btn btn-dark d-grid ">
                                <i class="bx bxl-google me-2"></i>Login with Google
                            </a>
                        </div>
                        <p class="text-center mb-0">
                            <span>New on our platform?</span>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    <span>Create an account</span>
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <!-- /Register Basic Card -->
        </div>
    </div>
@endsection
