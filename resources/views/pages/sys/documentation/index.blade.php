@extends('layouts.sys.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <div class="page-pretitle">Others</div>
        <h2 class="page-title">Development Guide</h2>
    </div>
</div>
@endsection

@section('content')
@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/assets/sys/css/documentation.css') }}">
@endpush

<div class="alert alert-info mb-4">
    <i class="bx bx-info-circle me-2"></i>
    <strong>Documentation Updated!</strong> We've streamlined our documentation to be more concise and junior-developer friendly. 
    Old comprehensive docs are still available in the archive section below.
</div>

<div class="documentation-cards-grid">
    <!-- Quick Start Card -->
    <a href="{{ route('sys.documentation.show', 'README') }}" class="documentation-card-link">
        <div class="documentation-card">
            <div class="documentation-card-header text-center">
                <div class="documentation-card-icon">
                    <i class="bx bx-rocket bx-lg"></i>
                </div>
                <h4 class="documentation-card-title">Quick Start</h4>
            </div>
            <div class="documentation-card-body">
                <p class="documentation-card-description">
                    Get started quickly with installation steps, project structure, and common commands.
                </p>
            </div>
        </div>
    </a>

    <!-- Development Guide Card -->
    <a href="{{ route('sys.documentation.show', 'DEVELOPMENT_GUIDE') }}" class="documentation-card-link">
        <div class="documentation-card">
            <div class="documentation-card-header text-center">
                <div class="documentation-card-icon">
                    <i class="bx bx-code-alt bx-lg"></i>
                </div>
                <h4 class="documentation-card-title">Development Guide</h4>
            </div>
            <div class="documentation-card-body">
                <p class="documentation-card-description">
                    Learn core patterns: Service Pattern, CRUD, Authorization, and best practices.
                </p>
            </div>
        </div>
    </a>

    <!-- Frontend Guide Card -->
    <a href="{{ route('sys.documentation.show', 'FRONTEND_GUIDE') }}" class="documentation-card-link">
        <div class="documentation-card">
            <div class="documentation-card-header text-center">
                <div class="documentation-card-icon">
                    <i class="bx bx-palette bx-lg"></i>
                </div>
                <h4 class="documentation-card-title">Frontend Guide</h4>
            </div>
            <div class="documentation-card-body">
                <p class="documentation-card-description">
                    Vite asset bundling, layouts, JavaScript libraries, and UI patterns.
                </p>
            </div>
        </div>
    </a>

    <!-- Features Reference Card -->
    <a href="{{ route('sys.documentation.show', 'FEATURES') }}" class="documentation-card-link">
        <div class="documentation-card">
            <div class="documentation-card-header text-center">
                <div class="documentation-card-icon">
                    <i class="bx bx-cog bx-lg"></i>
                </div>
                <h4 class="documentation-card-title">Features Reference</h4>
            </div>
            <div class="documentation-card-body">
                <p class="documentation-card-description">
                    Quick reference for all features: Auth, DataTables, Media, Monitoring, and more.
                </p>
            </div>
        </div>
    </a>
</div>

<!-- Archive Section -->
<div class="mt-5">
    <h5 class="mb-3">
        <i class="bx bx-archive me-2"></i>
        Archived Documentation (Comprehensive)
    </h5>
    <p class="text-muted mb-3">
        Looking for more detailed documentation? These comprehensive guides are still available:
    </p>

    <div class="documentation-cards-grid">
        <!-- Project Overview Card -->
        <a href="{{ route('sys.documentation.show', 'archive/project-overview') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-layout bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Overview & Setup</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed project structure and setup guide</small>
                    </p>
                </div>
            </div>
        </a>

        <!-- Development Patterns Card -->
        <a href="{{ route('sys.documentation.show', 'archive/patterns-best-practices') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-code-alt bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Development Patterns</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Comprehensive coding standards and patterns</small>
                    </p>
                </div>
            </div>
        </a>

        <!-- UI Frontend Card -->
        <a href="{{ route('sys.documentation.show', 'archive/ui-frontend') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-palette bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">UI & Frontend</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed UI components and frontend guide</small>
                    </p>
                </div>
            </div>
        </a>

        <!-- Advanced Features Card -->
        <a href="{{ route('sys.documentation.show', 'archive/advanced-features') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-cog bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Advanced Features</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed advanced features guide</small>
                    </p>
                </div>
            </div>
        </a>

        <!-- Other archived docs -->
        <a href="{{ route('sys.documentation.show', 'archive/authentication') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-shield-alt bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Authentication</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed authentication guide</small>
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('sys.documentation.show', 'archive/crud-operations') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-table bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">CRUD Operations</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed CRUD implementation guide</small>
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('sys.documentation.show', 'archive/media-management') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-images bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Media Management</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed media handling guide</small>
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('sys.documentation.show', 'archive/monitoring-backup') }}" class="documentation-card-link">
            <div class="documentation-card opacity-75">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-server bx-lg"></i>
                    </div>
                    <h4 class="documentation-card-title">Monitoring & Backup</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        <small class="text-muted">Archived - Detailed monitoring guide</small>
                    </p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
