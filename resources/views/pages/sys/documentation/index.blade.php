@extends('layouts.admin.app')

@section('title', 'System Documentation')

@push('css')
<link rel="stylesheet" href="{{ asset('assets-admin/css/documentation.css') }}">
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="documentation-cards-container">
        <h3 class="documentation-section-title">Documentation Center</h3>
        <p class="text-muted">Browse through our comprehensive documentation sections to learn more about the system.</p>

        <div class="documentation-cards-grid">
            <!-- Project Overview Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-cube"></i>
                    </div>
                    <h4 class="documentation-card-title">Project Overview & Setup</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Learn about the project structure, initial setup, system requirements, and configuration process.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'project-overview') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Authentication Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-lock"></i>
                    </div>
                    <h4 class="documentation-card-title">Authentication & Authorization</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Understand user authentication, role-based access control, and permission management features.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'authentication') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- CRUD Operations Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-table"></i>
                    </div>
                    <h4 class="documentation-card-title">CRUD Operations & Data Management</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Learn how to implement and manage Create, Read, Update, Delete operations in your applications.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'crud-operations') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- UI Frontend Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-palette"></i>
                    </div>
                    <h4 class="documentation-card-title">UI & Frontend Features</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Understand the UI components, layout structure, and frontend development patterns.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'ui-frontend') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Media Management Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-images"></i>
                    </div>
                    <h4 class="documentation-card-title">Media Management</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Learn how to handle file uploads, image processing, and media library integration.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'media-management') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Monitoring Backup Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-server"></i>
                    </div>
                    <h4 class="documentation-card-title">System Monitoring & Backup</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Understand system monitoring, server health, and backup management features.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'monitoring-backup') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Helper Functions Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-wrench"></i>
                    </div>
                    <h4 class="documentation-card-title">Helper Functions & Utilities</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Explore system and global helper functions for common operations and utilities.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'helpers-utilities') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Advanced Features Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-cog"></i>
                    </div>
                    <h4 class="documentation-card-title">Advanced Features</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Discover advanced system features including activity logging, notifications, and more.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'advanced-features') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Development Patterns Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-code-alt"></i>
                    </div>
                    <h4 class="documentation-card-title">Development Patterns</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Learn about coding standards, best practices, and development patterns used in the system.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'patterns-best-practices') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Database Models Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-data"></i>
                    </div>
                    <h4 class="documentation-card-title">Database & Models</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Understand database schema, Eloquent models, relationships, and migrations.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'database-models') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Testing QA Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-test-tube"></i>
                    </div>
                    <h4 class="documentation-card-title">Testing & Quality Assurance</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Learn about unit tests, feature tests, and quality assurance practices.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'testing') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>

            <!-- Deployment Card -->
            <div class="documentation-card">
                <div class="documentation-card-header text-center">
                    <div class="documentation-card-icon">
                        <i class="bx bx-globe"></i>
                    </div>
                    <h4 class="documentation-card-title">Deployment & Production</h4>
                </div>
                <div class="documentation-card-body">
                    <p class="documentation-card-description">
                        Understand deployment process, production configuration, and maintenance tasks.
                    </p>
                </div>
                <div class="documentation-card-footer">
                    <a href="{{ route('sys.documentation.show', 'deployment-production') }}" class="documentation-card-link">
                        View Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
