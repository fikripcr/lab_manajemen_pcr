@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Status Pegawai" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.status-pegawai.create') }}" data-modal-title="Tambah Status Pegawai" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'status-pegawai-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'status-pegawai-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="status-pegawai-table"
            route="{{ route('hr.status-pegawai.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status Pegawai'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
