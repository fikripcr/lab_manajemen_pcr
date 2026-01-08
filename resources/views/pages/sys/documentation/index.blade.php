@extends('layouts.sys.app')

@section('title', 'Documentation')

@section('header')
<x-sys.page-header title="Development Guide" pretitle="Others" />
@endsection

@section('content')




<div class="row row-cards">
    <!-- Quick Start Card -->
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('sys.documentation.show', 'README') }}" class="card card-link card-link-pop h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" /><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                </div>
                <h3 class="card-title mb-1">Quick Start</h3>
                <div class="text-muted">
                    Get started quickly with installation steps, project structure, and commands.
                </div>
            </div>
        </a>
    </div>

    <!-- Development Guide Card -->
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('sys.documentation.show', 'DEVELOPMENT_GUIDE') }}" class="card card-link card-link-pop h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" /></svg>
                </div>
                <h3 class="card-title mb-1">Development Guide</h3>
                <div class="text-muted">
                    Core patterns: Service Pattern, CRUD, Authorization, and best practices.
                </div>
            </div>
        </a>
    </div>

    <!-- Frontend Guide Card -->
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('sys.documentation.show', 'FRONTEND_GUIDE') }}" class="card card-link card-link-pop h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-purple" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                </div>
                <h3 class="card-title mb-1">Frontend Guide</h3>
                <div class="text-muted">
                    Vite asset bundling, layouts, JavaScript libraries, and UI patterns.
                </div>
            </div>
        </a>
    </div>

    <!-- Features Reference Card -->
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('sys.documentation.show', 'FEATURES') }}" class="card card-link card-link-pop h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-orange" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                </div>
                <h3 class="card-title mb-1">Features Reference</h3>
                <div class="text-muted">
                    Quick reference for all features: Auth, DataTables, Media, and more.
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Archive Section -->
<div class="mt-5">
    <div class="d-flex align-items-center mb-3">
        <h3 class="mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg>
            Archived Documentation
        </h3>
    </div>
    <p class="text-muted mb-4">
        Looking for more detailed documentation? These comprehensive guides are still available:
    </p>

    <div class="row row-cards">
        <!-- Project Overview Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/project-overview') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M4 13m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
                        <h4 class="card-title mb-0">Overview & Setup</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed project structure and setup guide
                    </div>
                </div>
            </a>
        </div>

        <!-- Development Patterns Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/patterns-best-practices') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" /></svg>
                        <h4 class="card-title mb-0">Development Patterns</h4>
                    </div>
                    <div class="text-muted small">
                        Comprehensive coding standards and patterns
                    </div>
                </div>
            </a>
        </div>

        <!-- UI Frontend Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/ui-frontend') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                        <h4 class="card-title mb-0">UI & Frontend</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed UI components and frontend guide
                    </div>
                </div>
            </a>
        </div>

        <!-- Advanced Features Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/advanced-features') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                        <h4 class="card-title mb-0">Advanced Features</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed advanced features guide
                    </div>
                </div>
            </a>
        </div>

        <!-- Authentication Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/authentication') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                        <h4 class="card-title mb-0">Authentication</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed authentication guide
                    </div>
                </div>
            </a>
        </div>

        <!-- CRUD Operations Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/crud-operations') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v16H4z" /><path d="M4 10h16" /><path d="M10 4v16" /></svg>
                        <h4 class="card-title mb-0">CRUD Operations</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed CRUD implementation guide
                    </div>
                </div>
            </a>
        </div>

        <!-- Media Management Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/media-management') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" /></svg>
                        <h4 class="card-title mb-0">Media Management</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed media handling guide
                    </div>
                </div>
            </a>
        </div>

        <!-- Monitoring & Backup Card -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('sys.documentation.show', 'archive/monitoring-backup') }}" class="card card-link card-link-pop h-100 opacity-75">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 12m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M7 8l0 .01" /><path d="M7 16l0 .01" /></svg>
                        <h4 class="card-title mb-0">Monitoring & Backup</h4>
                    </div>
                    <div class="text-muted small">
                        Detailed monitoring guide
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
