@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Jabatan Fungsional" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="create" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.jabatan-fungsional.create') }}" data-modal-title="Tambah Jabatan Fungsional" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card class="overflow-hidden">
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'jabatan-fungsional-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'jabatan-fungsional-table'" />
            </div>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="jabatan-fungsional-table"
            route="{{ route('hr.jabatan-fungsional.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'nama_jabatan', 'name' => 'nama_jabatan', 'title' => 'Nama Jabatan'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
