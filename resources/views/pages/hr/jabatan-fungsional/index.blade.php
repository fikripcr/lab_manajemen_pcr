@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Jabatan Fungsional" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.jabatan-fungsional.create') }}" data-modal-title="Tambah Jabatan Fungsional" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'jabatan-fungsional-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'jabatan-fungsional-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="jabatan-fungsional-table"
            route="{{ route('hr.jabatan-fungsional.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'nama_jabatan', 'name' => 'nama_jabatan', 'title' => 'Nama Jabatan'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
