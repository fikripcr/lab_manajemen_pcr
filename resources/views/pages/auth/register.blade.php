@extends('layouts.auth.app')

@section('content')
<x-tabler.card class="p-2">
    <x-tabler.card-body>
        <h2 class="h2 text-center mb-4">Buat akun baru</h2>
        
        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf
            
            <x-tabler.form-input 
                name="name" 
                label="Nama Lengkap" 
                value="{{ old('name') }}" 
                placeholder="Masukkan nama lengkap" 
                required="true" 
                autofocus
            />
            
            <x-tabler.form-input 
                name="email" 
                label="Email" 
                type="email" 
                value="{{ old('email') }}" 
                placeholder="Masukkan email" 
                required="true" 
            />
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="Password Anda" 
                           autocomplete="new-password" 
                           required>
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" data-bs-toggle="tooltip" title="Tampilkan password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </a>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" id="password_confirmation" class="form-control" 
                           name="password_confirmation" 
                           placeholder="Ketik ulang password" 
                           autocomplete="new-password" 
                           required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-check">
                    <input type="checkbox" id="terms" class="form-check-input" name="terms" required />
                    <span class="form-check-label">Menyetujui <a href="#" tabindex="-1">syarat dan ketentuan</a>.</span>
                </label>
            </div>
            
            <div class="form-footer">
                <x-tabler.button type="submit" class="w-100" text="Buat akun baru" icon="" />
            </div>
        </form>
    </x-tabler.card-body>
</x-tabler.card>

<div class="text-center text-secondary mt-3">
    Sudah punya akun? <a href="{{ route('login') }}" tabindex="-1">Login</a>
</div>
@endsection
