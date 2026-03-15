@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$page->title" pretitle="Detail Halaman">
    <x-slot:actions>
        <a href="{{ route('cms.public-page.edit', $page->encrypted_page_id) }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-edit"></i> Edit Halaman
        </a>
        <x-tabler.button type="back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row row-cards">
            <div class="col-lg-8">
                <x-tabler.card>
                    <x-tabler.card-body>
                        @if($page->hasMedia('main_image'))
                            <div class="mb-3">
                                <img src="{{ $page->getFirstMediaUrl('main_image') }}" alt="{{ $page->title }}" class="img-fluid rounded w-100 object-cover" style="max-height: 400px;">
                            </div>
                        @endif

                        <div class="typography">
                            {!! $page->content !!}
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>

            <div class="col-lg-4">
                <x-tabler.card class="mb-3">
                    <x-tabler.card-header>
                        <h3 class="card-title">Informasi Halaman</h3>
                    </x-tabler.card-header>
                    <x-tabler.card-body>
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
                            <small class="text-muted">Oleh: {{ $page->updated_by ?? '-' }}</small>
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>

                @if($page->hasMedia('attachments'))
                <x-tabler.card>
                    <x-tabler.card-header>
                        <h3 class="card-title">File Pendukung</h3>
                    </x-tabler.card-header>
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
                </x-tabler.card>
                @endif
            </div>
        </div>
@endsection
