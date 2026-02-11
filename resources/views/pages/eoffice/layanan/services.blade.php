@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Home">
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($grouped as $category => $items)
        <div class="col-12">
            <h2 class="page-title mb-3">
                {{ strtoupper($category) }}
            </h2>
        </div>
        @foreach($items as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card card-stacked">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-md bg-blue-lt">
                                <i class="ti ti-file-description fs-2"></i>
                            </span>
                            <div class="ms-3">
                                <h3 class="card-title mb-1">{{ $item->nama_layanan }}</h3>
                                <div class="text-muted small">
                                    Est. Pengerjaan: {{ $item->batas_pengerjaan }} Jam
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">
                            Layanan ini tersedia untuk: 
                            @foreach($item->only_show_on ?? [] as $role)
                                <span class="badge badge-outline text-blue">{{ $role }}</span>
                            @endforeach
                        </p>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('eoffice.layanan.create', $item->jenislayanan_id) }}" class="btn btn-primary">
                            <i class="ti ti-arrow-right me-1"></i> Ajukan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-mood-empty fs-1"></i>
                </div>
                <p class="empty-title">Belum ada layanan aktif</p>
                <p class="empty-subtitle text-muted">Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
