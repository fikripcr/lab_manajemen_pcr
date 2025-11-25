@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
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
                    <x-sys.datatable-search :dataTableId="'permissions-table'" />
                </div>
                <div>
                    <x-sys.datatable-filter :dataTableId="'permissions-table'" >
                        <div>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="sub_category" class="form-select">
                                <option value="">All Sub Categories</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory }}">{{ $subCategory }}</option>
                                @endforeach
                            </select>
                        </div>
                    </x-sys.datatable-filter>
                </div>
            </div>
        </div>

        <div class="card-body">
            <x-sys.flash-message />

            <x-sys.datatable id="permissions-table" route="{{ route('sys.permissions.data') }}" checkbox="true"  :columns="[
                [
                    'title' => '#',
                    'data' => 'DT_RowIndex',
                    'name' => 'DT_RowIndex',
                    'orderable' => false,
                    'searchable' => false,
                ],
                [
                    'title' => 'Name',
                    'data' => 'name',
                    'orderable' => true,
                    'name' => 'name',
                ],
                [
                    'title' => 'Category',
                    'data' => 'category',
                    'orderable' => true,
                    'name' => 'category',
                ],
                [
                    'title' => 'Sub Category',
                    'data' => 'sub_category',
                    'orderable' => true,
                    'name' => 'sub_category',
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
        const editPermissionUrl = "{{ route('sys.permissions.edit', ['id' => '__id__']) }}";
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show loading spinner in modal
            function showLoadingSpinner() {
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
            }

            // Handle create permission
            document.getElementById('createPermissionBtn').addEventListener('click', function() {
                // Show loading spinner initially
                showLoadingSpinner();
                const modal = new bootstrap.Modal(document.getElementById('modalAction'));
                modal.show();

                axios.get("{{ route('sys.permissions.create') }}")
                    .then(response => {
                        document.getElementById('modalContent').innerHTML = response.data;
                    })
                    .catch(() => {
                        showErrorMessage('Error!', 'Could not load form');
                    });
            });

            // Handle edit permission (event delegation)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.edit-permission');
                if (!btn) return;

                const id = btn.dataset.id;

                // Show loading spinner initially
                showLoadingSpinner();
                const modal = new bootstrap.Modal(document.getElementById('modalAction'));
                modal.show();

                axios.get(editPermissionUrl.replace('__id__', id))
                    .then(response => {
                        document.getElementById('modalContent').innerHTML = response.data;
                    })
                    .catch((e) => {
                        modal.hide()
                        showErrorMessage('Error!', 'Could not load form');
                    });
            });

        });


    </script>
@endpush
