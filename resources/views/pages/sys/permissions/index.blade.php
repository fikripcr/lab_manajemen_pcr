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
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length :dataTableId="'permissions-table'" />
                </div>
                <div>
                    <x-sys.datatable-search-filter :dataTableId="'permissions-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-sys.flash-message />

            <x-sys.datatable id="permissions-table" route="{{ route('permissions.data') }}" with-checkbox="true" checkbox-key="id"
             :columns="[
                [
                    'title' => '#',
                    'data' => 'DT_RowIndex',
                    'name' => 'DT_RowIndex',
                    'orderable' => true,
                    'searchable' => false,
                ],
                [
                    'title' => 'Name',
                    'data' => 'name',
                    'orderable' => true,
                    'name' => 'name',
                ],
                [
                    'title' => 'Roles Assigned',
                    'data' => 'roles_count',
                    'name' => 'roles_count',
                    'orderable' => true,
                    'searchable' => false,
                ],
                [
                    'title' => 'Created At',
                    'data' => 'created_at',
                    'orderable' => true,
                    'name' => 'created_at',
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" />
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
        document.addEventListener('DOMContentLoaded', function() {
            // Handle create permission button click
            document.getElementById('createPermissionBtn').addEventListener('click', function() {
                // 1. Buka modal
                const modal = new bootstrap.Modal(document.getElementById('modalAction'));
                modal.show();

                axios.get('{{ route('permissions.create-modal') }}')
                    .then(response => {
                        document.getElementById('modalContent').innerHTML = response.data;
                    })
                    .catch(() => {
                        showErrorMessage('Error!', 'Could not load form');
                    });
            });

            // Handle edit permission (event delegation)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-permission')) {
                    const btn = e.target.closest('.edit-permission');
                    const id = btn.dataset.id;

                    const modal = new bootstrap.Modal(document.getElementById('modalAction'));
                    modal.show();

                    axios.get(`/sys/permissions/${id}/edit`, {
                            params: {
                                id: id
                            }
                        })
                        .then(response => {
                            document.getElementById('modalContent').innerHTML = response.data;
                        })
                        .catch(() => {
                            showErrorMessage('Error!', 'Could not load form');
                        });
                }
            });

        });
    </script>
@endpush
