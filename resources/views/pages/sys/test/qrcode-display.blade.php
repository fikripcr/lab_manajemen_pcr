@extends('layouts.tabler.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
    <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Test /</span> QR Code Display</h4>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">QR Code for: {{ $text }}</h5>
    </div>
    <div class="card-body text-center">
        <div class="qr-container" style="display: inline-block; padding: 20px; background: white; border: 1px solid #ddd;">
            {!! $qrCodeSvg !!}
        </div>
        <p class="mt-3">QR Code generated with text: "{{ $text }}"</p>
        <a href="{{ route('sys.test.index') }}" class="btn btn-secondary">Back to Test Features</a>
    </div>
</div>
@endsection

@push('css')
    <style>
        .qr-container {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        svg {
            max-width: 100%;
            height: auto;
        }
    </style>
@endpush
