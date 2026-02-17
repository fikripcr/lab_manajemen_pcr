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
                <div class="card card-md">
                    <div class="card-body">
                        <h3 class="h1">Welcome, {{ auth()->user()->name ?? 'User' }}!</h3>
                        <p class="text-secondary">Selamat datang di Sistem Informasi Manajemen Terpadu Politeknik Caltex Riau.</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <h3 class="mb-3">Pengumuman Terbaru</h3>
                <div class="row row-cards">
                    @forelse($recentNews as $news)
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            @if($news->cover_medium_url)
                            <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url({{ $news->cover_medium_url }})"></div>
                            @endif
                            <div class="card-body">
                                <h3 class="card-title">{{ $news->judul }}</h3>
                                <p class="text-secondary">{{ Str::limit(strip_tags($news->isi), 100) }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('shared.pengumuman.show', $news->pengumuman_id) }}" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
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
