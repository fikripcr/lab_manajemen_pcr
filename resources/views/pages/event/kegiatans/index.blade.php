@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen Kegiatan" pretitle="Kegiatan">
    <x-slot:actions>
        <a href="{{ route('Kegiatan.Kegiatans.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus icon"></i>
            Tambah Kegiatan
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-Kegiatans"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'Kegiatan_id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'judul_Kegiatan', 'name' => 'judul_Kegiatan', 'title' => 'Judul Kegiatan'],
                        ['data' => 'jenis_Kegiatan', 'name' => 'jenis_Kegiatan', 'title' => 'Jenis'],
                        ['data' => 'tanggal_info', 'name' => 'tanggal_mulai', 'title' => 'Tanggal'],
                        ['data' => 'lokasi', 'name' => 'lokasi', 'title' => 'Lokasi'],
                        ['data' => 'pic.name', 'name' => 'pic.name', 'title' => 'PIC', 'defaultContent' => '-'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                    ]"
                    :route="route('Kegiatan.Kegiatans.data')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
