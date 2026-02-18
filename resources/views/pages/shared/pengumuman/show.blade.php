@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pengumuman->judul }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.pengumuman.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            @if($pengumuman->cover_url)
            <div class="card-img-top img-responsive img-responsive-21x9" style="background-image: url({{ $pengumuman->cover_url }})"></div>
            @endif
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-blue">{{ $pengumuman->jenis }}</span>
                    <span class="text-secondary ms-2">{{ $pengumuman->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="markdown">
                    {!! $pengumuman->isi !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
