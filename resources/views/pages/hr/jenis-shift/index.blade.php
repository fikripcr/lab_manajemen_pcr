@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Jenis Shift" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.jenis-shift.create') }}" data-modal-title="Tambah Jenis Shift" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'jenis-shift-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'jenis-shift-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="jenis-shift-table"
            route="{{ route('hr.jenis-shift.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'jenis_shift', 'name' => 'jenis_shift', 'title' => 'Nama Shift'],
                ['data' => 'jam_masuk', 'name' => 'jam_masuk', 'title' => 'Jam Masuk'],
                ['data' => 'jam_pulang', 'name' => 'jam_pulang', 'title' => 'Jam Pulang'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
