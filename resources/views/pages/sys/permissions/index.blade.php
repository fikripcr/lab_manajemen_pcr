@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Access Control /</span> Permission</h4>
        <button type="button" class="btn btn-primary ajax-modal-btn" data-url="{{ route('sys.permissions.create') }}">
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

            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-2">
                <div class="mb-2 mb-md-0">
                    <x-sys.datatable-info :dataTableId="'permissions-table'" />
                </div>
                <div>
                    <x-sys.datatable-pagination :dataTableId="'permissions-table'" />
                </div>
            </div>
        </div>
    </div>

@endsection
