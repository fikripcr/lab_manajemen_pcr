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
                    Beranda
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
            @if($slideshows->count() > 0)
            <div class="col-12">
                <div id="carousel-slideshow" class="carousel slide card shadow-sm border-0" data-bs-ride="carousel" style="border-radius: 12px; overflow: hidden;">
                    <div class="carousel-indicators">
                        @foreach($slideshows as $index => $slide)
                        <button type="button" data-bs-target="#carousel-slideshow" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($slideshows as $index => $slide)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $slide->image_url) }}" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="{{ $slide->title }}">
                            @if($slide->title || $slide->caption)
                            <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); border-radius: 8px; padding: 1rem; bottom: 2rem;">
                                @if($slide->title) <h3 class="fw-bold">{{ $slide->title }}</h3> @endif
                                @if($slide->caption) <p>{{ $slide->caption }}</p> @endif
                                @if($slide->link)
                                <x-tabler.button href="{{ $slide->link }}" class="btn-primary btn-sm mt-2" text="Selengkapnya" />
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-slideshow" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-slideshow" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            @endif

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

            <div class="col-lg-4 mt-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="m-0 fw-bold"><i class="ti ti-speakerphone me-2 text-primary"></i> Pengumuman</h3>
                </div>
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <ul class="steps steps-vertical">
                            @forelse($recentAnnouncements as $announcement)
                            <li class="step-item">
                                <div class="h4 m-0">
                                    <a href="{{ route('shared.pengumuman.show', $announcement->pengumuman_id) }}" class="text-reset">{{ $announcement->judul }}</a>
                                </div>
                                <div class="text-secondary small">{{ $announcement->created_at->format('d M Y') }}</div>
                                <div class="text-muted small mt-1 text-truncate" style="max-width: 250px;">
                                    {{ Str::limit(strip_tags($announcement->isi), 50) }}
                                </div>
                            </li>
                            @empty
                            <p class="text-secondary small">Tidak ada pengumuman terbaru.</p>
                            @endforelse
                        </ul>
                        <div class="mt-3 border-top pt-3">
                            <x-tabler.button href="{{ route('shared.pengumuman.index') }}" class="btn-ghost-primary btn-sm w-100" text="Lihat Semua" icon="ti ti-chevron-right" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mt-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="m-0 fw-bold"><i class="ti ti-news me-2 text-primary"></i> Berita Terbaru</h3>
                </div>
                <div class="row row-cards">
                    @forelse($recentNews as $news)
                    <div class="col-md-6 mb-3">
                        <div class="card card-stacked shadow-sm h-100 border-0 hvr-shadow" style="border-radius: 12px; overflow: hidden;">
                            @if($news->cover_medium_url)
                            <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url({{ $news->cover_medium_url }})"></div>
                            @else
                            <div class="img-responsive img-responsive-21x9 card-img-top bg-light d-flex align-items-center justify-content-center">
                                <i class="ti ti-news text-muted-opacity fs-1"></i>
                            </div>
                            @endif
                            <div class="card-body d-flex flex-column p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-muted small"><i class="ti ti-calendar-event me-1"></i> {{ $news->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="card-title mb-2">
                                    <a href="{{ route('shared.pengumuman.show', $news->pengumuman_id) }}" class="text-dark text-decoration-none">{{ $news->judul }}</a>
                                </h4>
                                <p class="text-secondary small mb-3 flex-grow-1">{{ Str::limit(strip_tags($news->isi), 80) }}</p>
                                <div class="mt-auto pt-2 border-top">
                                    <a href="{{ route('shared.pengumuman.show', $news->pengumuman_id) }}" class="small fw-bold text-primary">
                                        Baca Lengkap <i class="ti ti-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <p class="text-secondary small">Tidak ada berita terbaru.</p>
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
