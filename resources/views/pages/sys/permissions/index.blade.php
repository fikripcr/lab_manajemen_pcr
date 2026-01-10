@extends('layouts.sys.app')

@section('title', 'Permissions')

@section('header')
{{-- Page Header Content --}}
    <x-sys.page-header title="Permissions" pretitle="Access Control">
        <x-slot:actions>
            <x-sys.button type="create" :modal-url="route('sys.permissions.create')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
{{-- Page Body Content --}}
<div class="card overflow-hidden">
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
                        <x-form.select2 name="category" placeholder="All Categories" :options="$categories" />
                    </div>
                    <div>
                        <x-form.select2 name="sub_category" placeholder="All Sub Categories" :options="$subCategories" />
                    </div>
                </x-sys.datatable-filter>
            </div>
        </div>
    </div>

    <div class="card-body p-0">

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
        ]" :order="[[4, 'desc']]" />
    </div>
</div>
@endsection
