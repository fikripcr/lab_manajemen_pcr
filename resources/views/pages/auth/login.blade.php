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
            
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com" autocomplete="off" value="{{ old('email', 'user1@contoh-lab.ac.id') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-label">
                    Password
                    @if (Route::has('password.request'))
                    <span class="form-label-description">
                        <a href="{{ route('password.request') }}">I forgot password</a>
                    </span>
                    @endif
                </label>
                <div class="input-group input-group-flat">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Your password" autocomplete="off" value="password">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                        </a>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
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
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
    </div>
    
    <div class="hr-text">or</div>
    
    <div class="card-body">
        <div class="row">
            <div class="col">
                <a href="{{ route('auth.google') }}" class="btn btn-white w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-github" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" /></svg>
                     Login with Google
                </a>
            </div>
        </div>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
</div>
@endsection

