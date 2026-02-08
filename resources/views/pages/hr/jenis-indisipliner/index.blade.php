@extends('layouts.admin.app')

@section('title', 'Data Jenis Indisipliner')

@section('header')
<x-tabler.page-header title="Data Jenis Indisipliner" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.jenis-indisipliner.create') }}" data-modal-title="Tambah Jenis Indisipliner" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'jenis-indisipliner-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'jenis-indisipliner-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.flash-message />
        <x-tabler.datatable 
            id="jenis-indisipliner-table"
            route="{{ route('hr.jenis-indisipliner.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                ['data' => 'jenis_indisipliner', 'name' => 'jenis_indisipliner', 'title' => 'Jenis Indisipliner'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '100px'],
            ]"
        />
    </div>
</div>
@endsection
