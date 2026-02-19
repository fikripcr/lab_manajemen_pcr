@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Pengumuman" pretitle="Shared Data" />
@endsection

@section('content')
    <div class="row row-cards">
        @forelse($pengumumans as $pengumuman)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                @if($pengumuman->cover_medium_url)
                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url({{ $pengumuman->cover_medium_url }})"></div>
                @endif
                <div class="card-body">
                    <h3 class="card-title">{{ $pengumuman->judul }}</h3>
                    <p class="text-secondary">{{ Str::limit(strip_tags($pengumuman->isi), 100) }}</p>
                </div>
                <div class="card-footer">
                    <x-tabler.button href="{{ route('shared.pengumuman.show', $pengumuman->pengumuman_id) }}" class="btn-primary btn-sm" text="Baca Selengkapnya" />
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-secondary">Tidak ada pengumuman.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $pengumumans->links() }}
    </div>
@endsection
