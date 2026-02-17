@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Detail Data</div>
                <h2 class="page-title">{{ $pengumuman->judul }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('shared.pengumuman.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
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
