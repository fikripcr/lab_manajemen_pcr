@extends('layouts.tabler.app')

@section('title', 'Error Log')

@section('header')
    <x-tabler.page-header title="Error Log" pretitle="System Log">
        <x-slot:actions>
            <x-tabler.button href="{{ route('sys.dashboard') }}" text="Kembali" icon="ti ti-arrow-left" class="btn-outline-secondary me-2" />
            <x-tabler.button type="button" class="btn-danger" onclick="confirmClearAll()" text="Clear All" title="Clear All Error Logs" icon="ti ti-trash" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.activity-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.activity-log.index') }}">
                    <i class="ti ti-activity me-1"></i> Activity Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.notifications.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.notifications.index') }}">
                    <i class="ti ti-bell me-1"></i> Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.error-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.error-log.index') }}">
                    <i class="ti ti-bug me-1"></i> Error Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('app-config') ? 'active fw-bold' : '' }}" href="{{ route('app-config') }}">
                    <i class="ti ti-settings me-1"></i> App Configuration
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="card shadow-none border">
            <div class="card-header border-bottom-0">
                <div class="d-flex flex-wrap justify-content-between p-2 w-100">
            <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'error-logs-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'error-logs-table'" />
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <x-tabler.datatable id="error-logs-table" route="{{ route('sys.error-log.data') }}" :columns="[
            [
                'title' => '#',
                'data' => 'DT_RowIndex',
                'name' => 'id',
                'orderable' => false,
                'searchable' => false,
                'class' => 'text-center',
            ],
            [
                'title' => 'Message',
                'data' => 'message',
                'name' => 'message',
            ],
            [
                'title' => 'Error Type',
                'data' => 'error_type',
                'name' => 'exception_class',
            ],
            [
                'title' => 'User',
                'data' => 'user_info',
                'name' => 'user.name',
            ],
            [
                'title' => 'Date',
                'data' => 'created_at',
                'name' => 'created_at',
            ],
            [
                'title' => 'Actions',
                'data' => 'action',
                'name' => 'action',
                'orderable' => false,
                'searchable' => false,
            ],
        ]" :order="[[4, 'desc']]" />
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm before clearing all error logs
            window.confirmClearAll = function() {
                showDeleteConfirmation(
                    'Are you sure?',
                    'This will permanently delete all error logs. This action cannot be undone.',
                    'Yes, clear all!'
                ).then((result) => {
                    if (result.isConfirmed) {
                        axios.post('{{ route('sys.error-log.clear-all') }}')
                            .then(function(response) {
                                if (response.data.success) {
                                    showSuccessMessage('Cleared!', response.data.message).then(() => {
                                        // Reload the DataTable to reflect changes
                                        $('#error-logs-table').DataTable().ajax.reload();
                                    });
                                } else {
                                    showErrorMessage('Error!', response.data.message || 'Failed to clear error logs');
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error);
                                showErrorMessage('Error!', 'An error occurred while clearing error logs');
                            });
                    }
                });
            }
        });
    </script>
@endpush
