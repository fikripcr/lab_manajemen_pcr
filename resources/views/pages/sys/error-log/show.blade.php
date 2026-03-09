@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal title="Error Log Details" method="none">
        <div class="table-responsive border rounded mb-3">
            <table class="table table-sm table-borderless table-vcenter mb-0">
                <tr>
                    <th class="w-25">Level</th>
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
                    <td class="text-break"><strong>{{ $errorLog->message }}</strong></td>
                </tr>
                <tr>
                    <th>File</th>
                    <td class="text-break font-monospace small">{{ $errorLog->file }}:{{ $errorLog->line }}</td>
                </tr>
                <tr>
                     <th>User</th>
                     <td>
                        @if ($errorLog->user)
                            {{ $errorLog->user->name }}
                        @else
                            <span class="text-muted fst-italic">Anonymous</span>
                        @endif
                     </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ formatTanggalIndo($errorLog->created_at) }}</td>
                </tr>
            </table>
        </div>

        <div class="mb-3">
            <label class="form-label">Stack Trace</label>
            <pre class="bg-dark text-light p-2 rounded small mb-0" style="max-height: 200px; overflow-y: auto; white-space: pre-wrap;">{{ $errorLog->formatted_trace }}</pre>
        </div>

        @if ($errorLog->context)
            <div class="mb-3">
                <label class="form-label">Context</label>
                <div class="accordion" id="contextAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContext">
                                Show Context Data
                            </button>
                        </h2>
                        <div id="collapseContext" class="accordion-collapse collapse" data-bs-parent="#contextAccordion">
                           <div class="accordion-body p-0">
                                <pre class="bg-light text-dark p-2 rounded small mb-0">{{ json_encode($errorLog->context, JSON_PRETTY_PRINT) }}</pre>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <x-slot:footer>
            <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Tutup" />
            <x-tabler.button :href="route('sys.error-log.show', $errorLog->id)" icon="ti ti-external-link" text="View Full Page" class="ms-auto" />
        </x-slot:footer>
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('title', 'Error Log')

    @section('header')
        <x-tabler.page-header title="Error Log" pretitle="System Log">
            <x-slot:actions>
                <x-tabler.button type="back" href="{{ route('sys.error-log.index') }}" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-md-12">
                <x-tabler.card class="mb-4">
                    <x-tabler.card-body>
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
                    </x-tabler.card-body>
                </x-tabler.card>

                <x-tabler.card class="mb-4">
                    <x-tabler.card-header title="Stack Trace" class="d-flex justify-content-between align-items-center" />
                    <x-tabler.card-body>
                        <pre class="bg-dark text-light p-3 rounded" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;">
                            {{ $errorLog->formatted_trace }}
                        </pre>
                    </x-tabler.card-body>
                </x-tabler.card>

                @if ($errorLog->context)
                    <div class="card mb-4">
                    <x-tabler.card class="mb-4">
                        <x-tabler.card-header title="Context Information" />
                        <x-tabler.card-body>
                            <pre class="bg-light text-dark p-3 rounded">{{ json_encode($errorLog->context, JSON_PRETTY_PRINT) }}</pre>
                        </x-tabler.card-body>
                    </x-tabler.card>
                @endif
            </div>
        </div>
    @endsection
@endif
