@extends('layouts.admin.app')

@section('title', 'Dashboard PMB')

@section('header')
<x-tabler.page-header title="Dashboard PMB" pretitle="Penerimaan Mahasiswa Baru" />
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        {{-- LOAD DASHBOARD BASED ON ROLE --}}
        @if(auth()->user()->hasRole('camaba'))
            @include('pages.pmb.partials.camaba-dashboard')
        @else
            @include('pages.pmb.partials.admin-dashboard')
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh untuk camaba dashboard
@if(auth()->user()->role === 'camaba')
setInterval(() => {
    // Refresh status setiap 30 detik
    window.location.reload();
}, 30000);
@endif

// Notification system
function showNotification(message, type = 'info') {
    const notification = `
        <div class="alert alert-${type} alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-${type === 'success' ? 'check' : 'info'} me-2"></i>
                    ${message}
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    `;
    
    const container = document.querySelector('.page-body .container-xl');
    container.insertAdjacentHTML('afterbegin', notification);
    
    // Auto remove setelah 5 detik
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}
</script>
@endpush
