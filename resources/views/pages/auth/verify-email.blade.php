@extends('layouts.auth.app')

@section('content')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
        <!-- Verify Email -->
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-4">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
                        <img src="{{ asset('images/digilab-crop.png') }}" class="img-fluid " style="height: 100px; " alt="Logo" />
                    </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-2">Verify Your Email Address 📧</h4>
                <p class="mb-4">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-4">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                    @csrf
                    <button class="btn btn-primary d-grid w-100">Resend Verification Email</button>
                </form>

                <div class="d-flex justify-content-between">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Verify Email -->
    </div>
</div>
@endsection

