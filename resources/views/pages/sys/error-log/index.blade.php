@extends('layouts.sys.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <div class="page-pretitle">System Log</div>
        <h2 class="page-title">Error Log</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-danger" onclick="confirmClearAll()" title="Clear All Error Logs">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                Clear All
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<x-sys.flash-message />

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between">
            <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                <div>
                    <x-sys.datatable-page-length :dataTableId="'error-logs-table'" />
                </div>
                <div>
                    <x-sys.datatable-search :dataTableId="'error-logs-table'" />
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <x-sys.datatable id="error-logs-table" route="{{ route('sys.error-log.data') }}" :columns="[
            [
                'title' => '#',
                'data' => 'DT_RowIndex',
                'name' => 'id',
                'orderable' => false,
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
                'data' => 'actions',
                'name' => 'actions',
                'orderable' => false,
                'searchable' => false,
            ],
        ]" />
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
