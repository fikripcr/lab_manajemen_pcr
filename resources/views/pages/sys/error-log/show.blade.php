@extends('layouts.sys.app')

@section('title', 'Error Log Detail')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">System / Error Logs /</span> Detail
    </h4>

    <!-- Success Message -->
    <x-sys.flash-message />

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Error Details</h5>
                    <div>
                        <a href="{{ route('sys.error-log.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Back to Logs
                        </a>
                        <button type="button" class="btn btn-danger btn-sm ms-2"
                                onclick="confirmDelete('{{ route('sys.error-log.destroy', encryptId($errorLog->id)) }}')">
                            <i class="bx bx-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tr>
                                <th width="200">Level</th>
                                <td>
                                    <span class="badge
                                        @if($errorLog->level === 'error') bg-danger
                                        @elseif($errorLog->level === 'warning') bg-warning
                                        @elseif($errorLog->level === 'info') bg-info
                                        @else bg-secondary @endif">
                                        {{ ucfirst($errorLog->level) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Message</th>
                                <td><strong>{{ $errorLog->message }}</strong></td>
                            </tr>
                            <tr>
                                <th>Exception Class</th>
                                <td><code>{{ $errorLog->exception_class }}</code></td>
                            </tr>
                            <tr>
                                <th>File</th>
                                <td><code>{{ $errorLog->file }}</code></td>
                            </tr>
                            <tr>
                                <th>Line</th>
                                <td><code>{{ $errorLog->line }}</code></td>
                            </tr>
                            <tr>
                                <th>URL</th>
                                <td><code>{{ $errorLog->context['url'] ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th>Method</th>
                                <td><code>{{ $errorLog->context['method'] ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th>IP Address</th>
                                <td>{{ $errorLog->context['ip_address'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>User Agent</th>
                                <td>{{ $errorLog->context['user_agent'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>
                                    @if($errorLog->user)
                                        <a href="{{ route('users.show', encryptId($errorLog->user_id)) }}">{{ $errorLog->user->name }}</a>
                                    @else
                                        Anonymous User
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ formatTanggalIndo($errorLog->created_at) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stack Trace Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Stack Trace</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-dark text-light p-3 rounded" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;">
{{ $errorLog->formatted_trace }}
                    </pre>
                </div>
            </div>

            <!-- Context Information -->
            @if($errorLog->context)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Context Information</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded">{{ json_encode($errorLog->context, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
