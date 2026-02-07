@extends('layouts.admin.app')

@section('title', 'Inventaris Lab: ' . $lab->name)

@section('header')
    <x-tabler.page-header :title="'Inventaris Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('labs.index')" />
            <x-tabler.button type="create" :href="route('labs.inventaris.create', $lab->encrypted_lab_id)" text="Create" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />
            <x-tabler.datatable
                id="inventaris-table"
                route="{{ route('labs.inventaris.paginate', $lab->encrypted_lab_id) }}"
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                    ['title' => 'Kode Inventaris', 'data' => 'kode_inventaris', 'name' => 'kode_inventaris'],
                    ['title' => 'Nama Alat', 'data' => 'nama_alat', 'name' => 'inventaris.nama_alat'],
                    ['title' => 'Jenis Alat', 'data' => 'jenis_alat', 'name' => 'inventaris.jenis_alat'],
                    ['title' => 'No Series', 'data' => 'no_series', 'name' => 'no_series'],
                    ['title' => 'Tanggal Penempatan', 'data' => 'tanggal_penempatan', 'name' => 'tanggal_penempatan'],
                    ['title' => 'Tanggal Penghapusan', 'data' => 'tanggal_penghapusan', 'name' => 'tanggal_penghapusan'],
                    ['title' => 'Status', 'data' => 'status', 'name' => 'status'],
                    ['title' => 'Aksi', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
                ]"
            />
        </div>
    </div>
@endsection
