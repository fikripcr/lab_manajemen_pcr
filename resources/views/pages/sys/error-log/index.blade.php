@extends('layouts.admin.app')

@section('title', 'Error Logs')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">System /</span> Error Logs
    </h4>

    <!-- Success Message -->
    <x-flash-message />

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Error Logs</h5>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-danger btn-sm me-2" 
                            onclick="confirmClearAll()" 
                            title="Clear All Error Logs">
                        <i class='bx bx-trash me-1'></i> Clear All
                    </button>
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'error-logs-table',
                'filters' => [
                    [
                        'id' => 'level-filter',
                        'name' => 'level',
                        'type' => 'select',
                        'placeholder' => 'Filter by Level',
                        'col_size' => 2,
                        'options' => [
                            '' => 'All Levels',
                            'error' => 'Error',
                            'warning' => 'Warning',
                            'info' => 'Info',
                        ],
                        'column' => 0 // The column index in the DataTable
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            <x-flash-message />

            <x-datatable.datatable id="error-logs-table" 
                                  route="{{ route('sys.error-log.data') }}" 
                                  :columns="[
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
</div>

<script>
    // Confirm before clearing all error logs
    function confirmClearAll() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete all error logs. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, clear all!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('sys.error-log.clear-all') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Cleared!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            // Reload the DataTable to reflect changes
                            $('#error-logs-table').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to clear error logs',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while clearing error logs',
                        icon: 'error'
                    });
                });
            }
        });
    }
</script>
@endsection