@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Status Aktifitas" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="create" :modal-url="route('hr.status-aktifitas.create')" modal-title="Tambah Status Aktifitas" text="Tambah Data" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card class="overflow-hidden">
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'status-aktifitas-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'status-aktifitas-table'" />
            </div>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="status-aktifitas-table"
            route="{{ route('hr.status-aktifitas.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'nama_status', 'name' => 'nama_status', 'title' => 'Nama Status'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
