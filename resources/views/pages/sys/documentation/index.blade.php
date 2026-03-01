@extends('layouts.tabler.app')

@section('title', 'Documentation')

@section('header')
<x-tabler.page-header title="Documentation" pretitle="System">
    <x-slot:actions>
        <x-tabler.button href="{{ route('sys.dashboard') }}" class="me-2 btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg me-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h2m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0" /><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /></svg>
                <div>
                    <h4 class="alert-title">Project Documentation</h4>
                    <div class="text-secondary">
                        Comprehensive guides, standards, and references for the Laravel Boilerplate project. 
                        All documentation is written in Markdown and stored in the <code>docs/</code> directory.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $mainDocs = collect($docs)->where('category', 'main')->sortBy('title');
    $archiveDocs = collect($docs)->where('category', 'archive')->sortBy('title');
@endphp

<!-- Main Documentation Section -->
<div class="row row-cards">
    <div class="col-12">
        <div class="d-flex align-items-center mb-3">
            <h3 class="mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" /><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                Core Documentation
            </h3>
        </div>
    </div>

    @forelse($mainDocs as $doc)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('sys.documentation.show', $doc['name']) }}" class="card card-link card-link-pop h-100 doc-card">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon icon-lg text-primary me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-1 text-primary">{{ $doc['title'] }}</h4>
                        </div>
                    </div>
                    <div class="text-muted small mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg>
                            {{ formatTanggalIndo($doc['lastUpdated']) }}
                        </div>
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 3v4a1 1 0 0 0 1 1h4" /><path d="M18 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                            {{ number_format($doc['size'] / 1024, 1) }} KB
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <span class="badge bg-primary-lt">
                            <i class="ti ti-external-link me-1"></i> View Docs
                        </span>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="empty">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                </div>
                <p class="empty-title">No documentation found</p>
                <p class="empty-subtitle text-muted">
                    Documentation files should be placed in the <code>docs/</code> directory.
                </p>
            </div>
        </div>
    @endforelse
</div>

<!-- Archive Documentation Section -->
@if($archiveDocs->count() > 0)
<div class="mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <h3 class="mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg>
                    Archived Documentation
                </h3>
                <span class="badge bg-muted ms-2">{{ $archiveDocs->count() }} files</span>
            </div>
            <p class="text-muted mb-4">
                Legacy documentation that may be outdated. Refer to core documentation for up-to-date information.
            </p>
        </div>
    </div>

    <div class="row row-cards">
        @foreach($archiveDocs as $doc)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <a href="{{ route('sys.documentation.show', $doc['name']) }}" class="card card-link card-link-pop h-100 doc-card archive-section">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="icon icon-lg text-muted me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="card-title mb-1 text-muted">{{ $doc['title'] }}</h4>
                            </div>
                        </div>
                        <div class="text-muted small mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4" /><path d="M12 16l0 .01" /><path d="M3 12a9 9 0 1 1 18 0a9 9 0 0 1 -18 0" /></svg>
                                {{ formatTanggalIndo($doc['lastUpdated']) }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <span class="badge bg-muted-lt">
                                <i class="ti ti-archive me-1"></i> Archived
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Quick Reference Section -->
<div class="mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h2m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0" /><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /></svg>
                Quick Reference
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 bg-surface-secondary rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M7 16l-2.5 -2.5" /><path d="M21 21l-2.5 -2.5" /><path d="M5 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /></svg>
                        <div>
                            <div class="fw-bold">PROJECT_STANDARDS.md</div>
                            <div class="text-muted small">Complete project standards and guidelines</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 bg-surface-secondary rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure me-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" /></svg>
                        <div>
                            <div class="fw-bold">Development Guide</div>
                            <div class="text-muted small">Service pattern, CRUD, and best practices</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 bg-surface-secondary rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple me-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                        <div>
                            <div class="fw-bold">Frontend Guide</div>
                            <div class="text-muted small">Vite, layouts, and JavaScript libraries</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/assets/sys/css/documentation-show.css') }}">
@endpush
