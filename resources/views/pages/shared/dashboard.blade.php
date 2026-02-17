@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Dashboard
                </h2>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card card-md border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1d273b 0%, #2c3e50 100%); color: white; border-radius: 12px;">
                    <div class="card-body d-flex align-items-center py-5">
                        <div class="me-4 d-none d-md-block">
                            <span class="avatar avatar-xl rounded-circle border border-2 border-white-50 shadow-sm" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0054a6&color=fff&size=128)"></span>
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-2">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                            <p class="fs-3 opacity-75 mb-4">Sistem Informasi Manajemen Terpadu Politeknik Caltex Riau</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('notifications.index') }}" class="btn btn-primary px-4 py-2 border-0 shadow-sm" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                    <i class="ti ti-bell me-2"></i> Notifikasi
                                </a>
                                <a href="{{ route('shared.pengumuman.index') }}" class="btn btn-outline-white px-4 py-2" style="border-color: rgba(255,255,255,0.3);">
                                    Lihat Semua Info
                                </a>
                            </div>
                        </div>
                        <div class="ms-auto d-none d-lg-block opacity-25">
                            <i class="ti ti-school fs-1" style="font-size: 8rem !important;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="m-0 fw-bold"><i class="ti ti-speakerphone me-2 text-primary"></i> Pengumuman Terbaru</h3>
                    <div class="ms-auto text-muted">
                        <a href="{{ route('shared.pengumuman.index') }}" class="small text-muted text-decoration-none">Explore All <i class="ti ti-chevron-right small"></i></a>
                    </div>
                </div>
                <div class="row row-cards">
                    @forelse($recentNews as $news)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-stacked shadow-sm h-100 hvr-shadow transition-all border-0">
                            @if($news->cover_medium_url)
                            <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url({{ $news->cover_medium_url }})"></div>
                            @else
                            <div class="img-responsive img-responsive-21x9 card-img-top bg-light d-flex align-items-center justify-content-center">
                                <i class="ti ti-news text-muted-opacity fs-1"></i>
                            </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-blue-lt me-2">{{ ucfirst($news->jenis) }}</span>
                                    <span class="text-muted small"><i class="ti ti-calendar-event me-1"></i> {{ $news->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="card-title mb-2 text-dark">{{ $news->judul }}</h4>
                                <p class="text-secondary small mb-4 flex-grow-1">{{ Str::limit(strip_tags($news->isi), 100) }}</p>
                                <div class="mt-auto pt-3 border-top">
                                    <a href="{{ route('shared.pengumuman.show', $news->pengumuman_id) }}" class="btn btn-ghost-primary w-100 d-flex align-items-center justify-content-center">
                                        Baca Lengkap <i class="ti ti-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-secondary">Tidak ada pengumuman terbaru.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
