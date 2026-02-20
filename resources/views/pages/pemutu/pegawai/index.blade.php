@extends('layouts.tabler.app')
@section('title', 'Pegawai')

@section('header')
<x-tabler.page-header title="Pegawai" pretitle="Master Data">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button class="btn-ghost-primary ajax-modal-btn" data-url="{{ route('pemutu.pegawai.import') }}" icon="ti ti-file-import" text="Impor Pegawai" />
            <x-tabler.button class="btn-primary ajax-modal-btn" data-url="{{ route('pemutu.pegawai.create') }}" icon="ti ti-plus" text="Tambah Pegawai" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="pegawai-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="pegawai-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            <x-tabler.datatable 
                id="pegawai-table" 
                :route="route('pemutu.pegawai.data')" 
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                    ['title' => 'Nama', 'data' => 'nama', 'name' => 'nama'],
                    ['title' => 'Email', 'data' => 'email', 'name' => 'email'],
                    ['title' => 'Unit', 'data' => 'org_unit_id', 'name' => 'orgUnit.name'],
                    ['title' => 'Jenis', 'data' => 'jenis', 'name' => 'jenis'],
                    ['title' => 'Linked', 'data' => 'user_id', 'name' => 'user_id', 'className' => 'text-center'],
                    ['title' => 'Actions', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
                ]" 
                :order="[[1, 'asc']]" 
            />
        </div>
    </div>
@endsection
