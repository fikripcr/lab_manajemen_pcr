@extends('layouts.sys.app')

@section('title', 'Error Log')

@section('header')
<x-sys.page-header title="Error Log" pretitle="System Log">
    <x-slot:actions>
        <x-sys.button type="delete" onclick="confirmClearAll()" text="Clear All" title="Clear All Error Logs" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')

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
