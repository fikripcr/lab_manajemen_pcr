@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Status Aktifitas" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.status-aktifitas.create') }}" data-modal-title="Tambah Status Aktifitas" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'status-aktifitas-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'status-aktifitas-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="status-aktifitas-table"
            route="{{ route('hr.status-aktifitas.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                ['data' => 'kode_status', 'name' => 'kode_status', 'title' => 'Kode'],
                ['data' => 'nama_status', 'name' => 'nama_status', 'title' => 'Nama Status'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
            ]"
        />
    </div>
</div>
@endsection
