@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Buku Tamu Kegiatan" pretitle="Kegiatan">
    <x-slot:actions>
        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-ajax" data-url="{{ route('Kegiatan.tamus.create') }}">
            <i class="ti ti-plus icon"></i>
            Tambah Tamu
        </button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-tamus"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'Kegiatantamu_id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'foto_preview', 'name' => 'foto_preview', 'title' => 'Foto', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_tamu', 'name' => 'nama_tamu', 'title' => 'Nama Tamu'],
                        ['data' => 'instansi', 'name' => 'instansi', 'title' => 'Instansi'],
                        ['data' => 'Kegiatan.judul_Kegiatan', 'name' => 'Kegiatan.judul_Kegiatan', 'title' => 'Kegiatan'],
                        ['data' => 'waktu_datang', 'name' => 'waktu_datang', 'title' => 'Waktu Datang'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                    ]"
                    :route="route('Kegiatan.tamus.data')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
