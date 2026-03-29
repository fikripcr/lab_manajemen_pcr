@extends('layouts.auth.app')

@section('content')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
        <!-- Verify Email -->
        <x-tabler.card>
            <x-tabler.card-body>
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-4">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
                        <img src="{{ asset('images/logo-apps.png') }}" class="img-fluid " style="height: 100px; " alt="Logo" />
                    </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-2">Verifikasi Alamat Email Anda 📧</h4>
                <p class="mb-4">Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima emailnya, kami dengan senang hati akan mengirimkan yang baru.</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-4">
                        {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                    @csrf
                    <x-tabler.button type="submit" class="w-100" text="Kirim Ulang Email Verifikasi" icon="" />
                </form>

                <div class="d-flex justify-content-between">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-tabler.button type="submit" style="link" class="text-muted" text="Logout" icon="" />
                    </form>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
        <!-- /Verify Email -->
    </div>
</div>
@endsection
