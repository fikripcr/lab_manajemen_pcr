@extends(auth()->check() ? 'layouts.admin.app' : 'layouts.public.app')

@section('content')
<div class="page-body">
    <div class="container-tight py-4">
        <div class="empty">
            <div class="empty-icon">
                <i class="ti ti-circle-check text-success" style="font-size: 4rem;"></i>
            </div>
            <p class="empty-title">Terima Kasih!</p>
            <p class="empty-subtitle text-muted">
                Jawaban Anda untuk survei <strong>{{ $survei->judul }}</strong> telah berhasil disimpan.
            </p>
            <div class="empty-action">
                @auth
                {{-- <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali ke Dashboard
                </a> --}}
                @else
                <x-tabler.button href="{{ url('/') }}" icon="ti ti-home" label="Kembali ke Beranda" />
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
