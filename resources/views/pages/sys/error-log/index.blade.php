@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Log/</span> Error</h4>
    </div>

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
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="confirmClearAll()" title="Clear All Error Logs">
                        <i class='bx bx-trash me-1'></i> Clear All
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-sys.flash-message />

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
