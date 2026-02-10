@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Manajemen Data Pegawai">
    <x-slot:actions>
        <a href="{{ route('hr.pegawai.upload-photo') }}" class="btn btn-outline-primary d-none d-sm-inline-block me-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/><path d="M4 13h3"/><path d="M4 17h5"/><path d="M21 4v6"/><path d="M18 4v2"/></svg>
            Upload Foto Pegawai
        </a>
        <a href="{{ route('hr.pegawai.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Tambah Pegawai
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'pegawai-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'pegawai-table'" />
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <x-tabler.flash-message />
        <x-tabler.datatable 
            id="pegawai-table"
            route="{{ route('hr.pegawai.index') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'nama_lengkap', 'name' => 'nama', 'title' => 'Nama Lengkap'],
                ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
                ['data' => 'unit', 'name' => 'unit', 'title' => 'Unit (Dept/Prodi)'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
            ]"
        />
    </div>
</div>
@endsection
