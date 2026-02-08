@extends('layouts.admin.app')

@section('title', 'Data Jenis Izin')

@section('header')
<x-tabler.page-header title="Data Jenis Izin" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.jenis-izin.create') }}" data-modal-title="Tambah Jenis Izin" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-tabler.datatable
            id="table-jenis-izin"
            route="{{ route('hr.jenis-izin.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Jenis Izin'],
                ['data' => 'kategori', 'name' => 'kategori', 'title' => 'Kategori'],
                ['data' => 'max_hari', 'name' => 'max_hari', 'title' => 'Max Hari', 'class' => 'text-center'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
        />
    </div>
</div>
@endsection
