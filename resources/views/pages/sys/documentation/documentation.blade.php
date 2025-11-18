@extends('layouts.admin.app')

@section('title', 'System Documentation')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">System Documentation</h4>
                    <small class="text-muted">Last updated: {{ \Carbon\Carbon::parse($lastUpdated)->format('d M Y H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="documentation-content">
                        {!! $htmlContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.documentation-content {
    max-width: 100%;
    overflow-x: auto;
}
.documentation-content h1 {
    font-size: 2rem;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 0.5rem;
}
.documentation-content h2 {
    font-size: 1.75rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #444;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 0.5rem;
}
.documentation-content h3 {
    font-size: 1.5rem;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    color: #555;
}
.documentation-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
}
.documentation-content ul {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.documentation-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}
.documentation-content pre.code-block {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    overflow-x: auto;
    margin: 1rem 0;
}
.documentation-content code.inline-code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}
.documentation-content a {
    color: #0d6efd;
    text-decoration: none;
}
.documentation-content a:hover {
    color: #0a58ca;
    text-decoration: underline;
}
</style>
@endsection