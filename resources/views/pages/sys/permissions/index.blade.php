@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Access Control /</span> Permission</h4>
        <button type="button" class="btn btn-primary" id="createPermissionBtn">
            <i class="bx bx-plus"></i> Add New Permission
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Permissions List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'permissions-table'
            ])
        </div>
        <div class="card-body">
            <x-flash-message />

            <x-datatable.datatable
                id="permissions-table"
                route="{{ route('permissions.data') }}"
                :columns="[
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false
                    ],
                    [
                        'title' => 'Name',
                        'data' => 'name',
                        'name' => 'name',
                    ],
                    [
                        'title' => 'Roles Assigned',
                        'data' => 'roles_count',
                        'name' => 'roles_count',
                        'orderable' => false,
                        'searchable' => false
                    ],
                    [
                        'title' => 'Created At',
                        'data' => 'created_at',
                        'name' => 'created_at'
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false
                    ]
                ]"
            />
        </div>
    </div>

    <!-- Modal container for create/edit operations -->
    <div class="modal fade" id="modalAction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="modalContent">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Handle create permission button click
        $('#createPermissionBtn').on('click', function() {

            $('#modalAction').modal('show');
            $.get('{{ route('permissions.create-modal') }}', function(data) {
                $('#modalContent').html(data);
            }).fail(function() {
                showErrorMessage('Error!', 'Could not load form');
            });
        });

        // Handle edit permission - using event delegation for dynamically added elements
        $(document).on('click', '.edit-permission', function() {
            var permissionId = $(this).data('id');
            $.get('{{ route('permissions.edit-modal.show', '') }}/' + permissionId, function(data) {
                $('#modalAction').modal('show');
                $('#modalContent').html(data);
            }).fail(function() {
                showErrorMessage('Error!', 'Could not load form');
            });
        });
    </script>
@endpush
