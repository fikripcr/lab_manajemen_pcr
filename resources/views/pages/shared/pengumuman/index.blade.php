@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Pengumuman" pretitle="Shared Data" />
@endsection

@section('content')
    <div class="row row-cards">
        @forelse($pengumumans as $pengumuman)
        <div class="col-md-6 col-lg-4">
            <x-tabler.card>
                @if($pengumuman->cover_medium_url)
                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url({{ $pengumuman->cover_medium_url }})"></div>
                @endif
                <x-tabler.card-header :title="$pengumuman->judul" />
                <x-tabler.card-body>
                    <p class="text-secondary">{{ Str::limit(strip_tags($pengumuman->isi), 100) }}</p>
                </x-tabler.card-body>
                <x-tabler.card-footer>
                    <x-tabler.button href="{{ route('shared.pengumuman.show', $pengumuman->pengumuman_id) }}" class="btn-primary btn-sm" text="Baca Selengkapnya" />
                </x-tabler.card-footer>
            </x-tabler.card>
        </div>
        @empty
        <div class="col-12">
            <x-tabler.card>
                <x-tabler.card-body>
                    <p class="text-secondary text-center">Tidak ada pengumuman.</p>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $pengumumans->links() }}
    </div>
@endsection
