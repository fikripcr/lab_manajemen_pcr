@extends('layouts.auth.app')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Create new account</h2>
        
        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" 
                       placeholder="Enter name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       placeholder="Enter email" 
                       value="{{ old('email') }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="Password" 
                           autocomplete="new-password" 
                           required>
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" data-bs-toggle="tooltip" title="Show password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </a>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" 
                           name="password_confirmation" 
                           placeholder="Confirm password" 
                           autocomplete="new-password" 
                           required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="terms" required />
                    <span class="form-check-label">Agree the <a href="#" tabindex="-1">terms and policy</a>.</span>
                </label>
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Create new account</button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    Already have account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
</div>
@endsection
