@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Posisi" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Posisi" class="ajax-modal-btn" data-url="{{ route('hr.posisi.create') }}" data-modal-title="Tambah Posisi" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'posisi-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'posisi-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="posisi-table"
            route="{{ route('hr.posisi.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Nama Posisi'],
                ['data' => 'alias', 'name' => 'alias', 'title' => 'Alias'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
