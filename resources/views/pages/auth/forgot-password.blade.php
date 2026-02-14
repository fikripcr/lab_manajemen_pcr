@extends('layouts.auth.app')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Forgot password</h2>
        <p class="text-secondary mb-4">Enter your email address and your password will be reset and emailed to you.</p>
        
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </div>
                    <div>
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
            @csrf
            
            <x-tabler.form-input 
                name="email" 
                label="Email address" 
                type="email" 
                value="{{ old('email') }}" 
                placeholder="Enter email" 
                required="true" 
                autofocus
            />
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                    Send me new password
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    Forget it, <a href="{{ route('login') }}">send me back</a> to the sign in screen.
</div>
@endsection
