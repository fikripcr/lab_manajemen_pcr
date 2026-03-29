@extends('layouts.tabler.app')

@section('title', $pageTitle)

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="System">
    <x-slot:actions>
        <x-tabler.button :href="url()->previous()" icon="ti ti-arrow-left" text="Back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Documents</div>
                    <div class="ms-auto">
                        <i class="ti ti-files text-muted"></i>
                    </div>
                </div>
                <div class="h1 mb-0">{{ $stats['total'] }}</div>
                <div class="text-muted small">Documentation files</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Categories</div>
                    <div class="ms-auto">
                        <i class="ti ti-category text-muted"></i>
                    </div>
                </div>
                <div class="h1 mb-0">{{ count($stats['by_category']) }}</div>
                <div class="text-muted small">Active categories</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Size</div>
                    <div class="ms-auto">
                        <i class="ti ti-database text-muted"></i>
                    </div>
                </div>
                <div class="h1 mb-0">{{ number_format($stats['total_size'] / 1024, 1) }} KB</div>
                <div class="text-muted small">Across all categories</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Last Updated</div>
                    <div class="ms-auto">
                        <i class="ti ti-clock text-muted"></i>
                    </div>
                </div>
                <div class="h1 mb-0 text-truncate" style="max-width: 150px;">
                    {{ $stats['last_updated'] ? \Carbon\Carbon::parse($stats['last_updated'])->format('d M Y') : '-' }}
                </div>
                <div class="text-muted small">Most recent update</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar with Category Tree -->
    <div class="col-lg-3">
        <div class="card sticky-top" style="top: 1rem; z-index: 100;">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="ti ti-category me-2"></i> Categories
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($tree as $category)
                        <a href="{{ route('sys.documentation.category', $category['name']) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $currentCategory === $category['name'] ? 'active' : '' }}"
                           title="{{ $category['display_name'] }}">
                            <div>
                                <i class="{{ $category['icon'] }} me-2"></i>
                                {{ $category['display_name'] }}
                            </div>
                            <span class="badge bg-muted">{{ count($category['children']) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('sys.documentation.index') }}" method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Search documentation..." value="{{ $search }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if($search)
                        <a href="{{ route('sys.documentation.index') }}" class="btn btn-secondary">
                            <i class="ti ti-x"></i>
                        </a>
                    @endif
                </form>
                @if($search)
                    <div class="mt-2 text-muted small">
                        Found {{ $docs->count() }} result(s) for "<strong>{{ $search }}</strong>"
                    </div>
                @endif
            </div>
        </div>

        <!-- Documentation Cards by Category -->
        @foreach($tree as $category)
            @php
                $categoryDocs = $docs->where('category', $category['name']);
            @endphp

            @if($categoryDocs->count() > 0 || empty($currentCategory))
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="mb-0">
                            <i class="{{ $category['icon'] }} me-2 text-primary"></i>
                            {{ $category['display_name'] }}
                        </h3>
                        <span class="badge bg-muted-lt ms-2">{{ $categoryDocs->count() }}</span>
                    </div>

                    <div class="row g-3">
                        @foreach($categoryDocs as $doc)
                            <div class="col-sm-6 col-lg-4">
                                <div class="card h-100 doc-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="icon icon-lg text-primary me-3">
                                                <i class="ti ti-file-text"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-1">
                                                    <a href="{{ route('sys.documentation.show', ['path' => $doc['name']]) }}" class="text-reset text-decoration-none">
                                                        {{ $doc['title'] }}
                                                    </a>
                                                </h5>
                                            </div>
                                        </div>

                                        <p class="text-muted small mb-3 doc-excerpt">
                                            {{ Str::limit($doc['excerpt'], 80) }}
                                        </p>

                                        <div class="text-muted small mb-3">
                                            <div class="mb-1">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($doc['lastUpdated'])->format('d M Y') }}
                                            </div>
                                            <div>
                                                <i class="ti ti-database me-1"></i>
                                                {{ number_format($doc['size'] / 1024, 1) }} KB
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('sys.documentation.show', ['path' => $doc['name']]) }}" class="btn btn-sm btn-primary stretched-link">
                                                <i class="ti ti-eye me-1"></i> View
                                            </a>
                                            @if($doc['order'] < 999)
                                                <span class="badge bg-muted-lt">
                                                    #{{ str_pad($doc['order'], 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($docs->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">{{ $search ? 'No results found' : 'No documentation found' }}</h4>
                    <p class="text-muted">
                        {{ $search ? 'Try different keywords or browse categories' : 'Documentation files should be placed in the docs/ directory.' }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ Vite::asset('resources/assets/sys/css/documentation.css') }}">
@endpush

@push('scripts')
<script src="{{ Vite::asset('resources/assets/sys/js/documentation.js') }}"></script>
@endpush
