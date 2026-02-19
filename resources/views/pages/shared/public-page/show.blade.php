@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$page->title" pretitle="Detail Halaman">
    <x-slot:actions>
        <a href="{{ route('shared.public-page.edit', $page->hashid) }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-edit"></i> Edit Halaman
        </a>
        <a href="javascript:void(0)" onclick="history.back()" class="btn btn-secondary d-none d-sm-inline-block">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if($page->hasMedia('main_image'))
                            <div class="mb-3">
                                <img src="{{ $page->getFirstMediaUrl('main_image') }}" alt="{{ $page->title }}" class="img-fluid rounded w-100 object-cover" style="max-height: 400px;">
                            </div>
                        @endif

                        <div class="typography">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Halaman</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            @if($page->is_published)
                                <span class="badge bg-success me-1"></span> Published
                            @else
                                <span class="badge bg-orange me-1"></span> Draft
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <div class="form-control-plaintext">{{ $page->slug }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terakhir Diupdate</label>
                            <div class="form-control-plaintext">{{ $page->updated_at->format('d M Y H:i') }}</div>
                            <small class="text-muted">Oleh: {{ $page->updatedBy->name ?? '-' }}</small>
                        </div>
                    </div>
                </div>

                @if($page->hasMedia('attachments'))
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">File Pendukung</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($page->getMedia('attachments') as $media)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="text-truncate me-3">
                                <i class="ti ti-file me-2"></i>
                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-reset text-truncate">
                                    {{ $media->file_name }}
                                </a>
                            </div>
                            <span class="badge bg-secondary-lt">{{ $media->human_readable_size }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
