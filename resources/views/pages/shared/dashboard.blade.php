@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Beranda" pretitle="Overview" />
@endsection

@section('content')
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
                <div class="card card-md border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1e3a5f 0%, #2c3e50 50%, #34495e 100%); color: white; border-radius: 12px;">
                    <div class="card-body d-flex align-items-center py-5 position-relative">
                        <div class="me-4 d-none d-md-block">
                            <span class="avatar avatar-xl rounded-circle border border-2 border-white-50 shadow-sm" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0054a6&color=fff&size=128)"></span>
                        </div>
                        <div class="flex-grow-1">
                            <h1 class="display-6 fw-bold mb-2">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                            <p class="fs-4 opacity-75 mb-4">Sistem Informasi Manajemen Terpadu Politeknik Caltex Riau</p>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('notifications.index') }}" class="btn btn-light px-4 py-2 shadow-sm">
                                    <i class="ti ti-bell me-2"></i> Notifikasi
                                </a>
                                <a href="{{ route('shared.pengumuman.index') }}" class="btn btn-outline-light px-4 py-2">
                                    <i class="ti ti-info-circle me-2"></i> Lihat Semua Info
                                </a>
                            </div>
                        </div>
                        <div class="ms-auto d-none d-lg-block opacity-10 position-absolute end-0 me-4">
                            <i class="ti ti-school" style="font-size: 10rem;"></i>
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
                            <li class="step-item pb-3">
                                <div class="h4 m-0 mb-2">
                                    <a href="{{ route('shared.pengumuman.show', $announcement->pengumuman_id) }}" class="text-reset fw-bold text-decoration-none">{{ $announcement->judul }}</a>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-clock me-1 text-muted" style="font-size: 0.875rem;"></i>
                                    <span class="text-muted small">{{ $announcement->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="text-secondary small lh-base" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ strip_tags($announcement->isi) }}
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
                <div class="vstack gap-3">
                    @forelse($recentNews as $news)
                    <div class="card border-0 shadow-sm hover-shadow-lg transition-shadow" style="border-radius: 12px; overflow: hidden;">
                        <div class="row g-0">
                            @if($news->cover_medium_url)
                            <div class="col-md-3 col-lg-2">
                                <img src="{{ $news->cover_medium_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $news->judul }}" style="min-height: 120px; max-height: 180px;">
                            </div>
                            @else
                            <div class="col-md-3 col-lg-2 bg-light d-flex align-items-center justify-content-center" style="min-height: 120px;">
                                <i class="ti ti-news text-muted fs-1"></i>
                            </div>
                            @endif
                            <div class="col-md-9 col-lg-10">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-calendar-event me-1 text-muted"></i>
                                        <span class="text-muted small">{{ $news->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="card-title mb-2 fw-semibold">
                                        <a href="{{ route('shared.pengumuman.show', $news->pengumuman_id) }}" class="text-dark text-decoration-none stretched-link">{{ $news->judul }}</a>
                                    </h4>
                                    <p class="text-secondary mb-3 small">{{ Str::limit(strip_tags($news->isi), 120) }}</p>
                                    <div class="d-flex align-items-center">
                                        <span class="small fw-bold text-primary">Baca Lengkap <i class="ti ti-arrow-right ms-1"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-news text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">Tidak ada berita terbaru.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
@endsection
