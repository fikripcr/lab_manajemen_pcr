@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Departemen" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Departemen" class="ajax-modal-btn" data-url="{{ route('hr.departemen.create') }}" data-modal-title="Tambah Departemen" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'departemen-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'departemen-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="departemen-table"
            route="{{ route('hr.departemen.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'departemen', 'name' => 'departemen', 'title' => 'Nama Departemen'],
                ['data' => 'abbr', 'name' => 'abbr', 'title' => 'Singkatan'],
                ['data' => 'alias', 'name' => 'alias', 'title' => 'Alias'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
