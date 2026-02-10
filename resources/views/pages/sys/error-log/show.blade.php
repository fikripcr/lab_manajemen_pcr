@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.sys.empty' : 'layouts.sys.app')

@section('title', 'Error Log')

@section('header')
    <x-tabler.page-header title="Error Log" pretitle="System Log">
        <x-slot:actions>
            <x-tabler.button type="back" url="{{ route('sys.error-log.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tr>
                                <th width="200">Level</th>
                                <td>
                                    <span class="badge
                                        @if ($errorLog->level === 'error') bg-red text-red-fg
                                        @elseif($errorLog->level === 'warning') bg-yellow text-yellow-fg
                                        @elseif($errorLog->level === 'info') bg-blue text-blue-fg
                                        @else bg-default @endif">
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
                                    @if ($errorLog->user)
                                        <a href="{{ route('lab.users.show', encryptId($errorLog->user_id)) }}">{{ $errorLog->user->name }}</a>
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

            @if ($errorLog->context)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Context Information</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light text-dark p-3 rounded">{{ json_encode($errorLog->context, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
