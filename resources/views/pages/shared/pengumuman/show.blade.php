@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pengumuman->judul }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.pengumuman.index') }}" type="back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <x-tabler.card>
            @if($pengumuman->cover_url)
            <div class="card-img-top img-responsive img-responsive-21x9" style="background-image: url({{ $pengumuman->cover_url }})"></div>
            @endif
            <x-tabler.card-body>
                <div class="mb-3">
                    <span class="badge bg-blue">{{ $pengumuman->jenis }}</span>
                    <span class="text-secondary ms-2">{{ $pengumuman->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="markdown">
                    {!! $pengumuman->isi !!}
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
@endsection
