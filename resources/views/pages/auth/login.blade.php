{{--
    Auth Layout Selection:
    Change $authLayout value to switch layouts:
    - 'basic' = Centered card (default)
    - 'cover' = Split screen with background image
    - 'illustration' = Side-by-side with illustration
--}}
@php
    // $authLayout is now handled by the layout or global view composer, with fallback in the layout file.
@endphp

@extends('layouts.auth.app')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Login to your account</h2>
        <form action="{{ route('login') }}" method="POST" autocomplete="off" novalidate>
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8l0 4" /><path d="M12 16l.01 0" /></svg>
                        </div>
                        <div>
                            <h4 class="alert-title">Login Failed</h4>
                            <div class="text-secondary">{{ $errors->first() }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <x-tabler.form-input 
                name="email" 
                label="Email address" 
                type="email" 
                value="{{ old('email', 'user1@contoh-lab.ac.id') }}" 
                placeholder="your@email.com" 
                required="true" 
                autofocus
            />

            <div class="mb-2">
                <label class="form-label">
                    Password
                    @if (Route::has('password.request'))
                    <span class="form-label-description">
                        <a href="{{ route('password.request') }}">I forgot password</a>
                    </span>
                    @endif
                </label>
                <div class="input-group input-group-flat @error('password') has-validation @enderror">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Your password" autocomplete="off" value="password">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                        </a>
                    </span>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-2">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                    <span class="form-check-label">Remember me on this device</span>
                </label>
            </div>

            <div class="form-footer">
                <x-tabler.button type="submit" class="w-100" text="Sign in" />
            </div>
        </form>
    </div>

    <div class="hr-text">or</div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <x-tabler.button href="{{ route('auth.google') }}" class="btn-white w-100" icon="brand-google" text="Login with Google" />
            </div>
        </div>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
</div>
@endsection

