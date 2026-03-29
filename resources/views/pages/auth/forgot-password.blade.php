@extends('layouts.auth.app')

@section('content')
<x-tabler.card class="p-2">
    <x-tabler.card-body>
        <h2 class="h2 text-center mb-4">Lupa password</h2>
        <p class="text-secondary mb-4">Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mereset password Anda.</p>
        
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
                label="Email" 
                type="email" 
                value="{{ old('email') }}" 
                placeholder="Masukkan email" 
                required="true" 
                autofocus
            />
            
            <div class="form-footer">
                <x-tabler.button type="submit" class="w-100" icon="ti ti-mail" text="Kirim link reset password" />
            </div>
        </form>
    </x-tabler.card-body>
</x-tabler.card>

<div class="text-center text-secondary mt-3">
    Abaikan, kembali ke halaman <a href="{{ route('login') }}">Login</a>.
</div>
@endsection
