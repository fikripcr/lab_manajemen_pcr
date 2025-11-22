@extends('layouts.auth.app')

@section('content')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
        <!-- Confirm Password Card -->
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-4">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
                        <img src="{{ asset('assets-global/img/digilab-crop.png') }}" class="img-fluid " style="height: 100px; " alt="Logo" />
                    </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-2">Confirm Password 🔐</h4>
                <p class="mb-4">This is a secure area of the application. Please confirm your password before continuing.</p>

                <form method="POST" action="{{ route('password.confirm') }}" class="mb-3">
                    @csrf

                    <div class="mb-3 form-password-toggle">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                   placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                   aria-describedby="password" required autocomplete="current-password" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary d-grid w-100" type="submit">Confirm Password</button>
                </form>
            </div>
        </div>
        <!-- /Confirm Password Card -->
    </div>
</div>
@endsection
