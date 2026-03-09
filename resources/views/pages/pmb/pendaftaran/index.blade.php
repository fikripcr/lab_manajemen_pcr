@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Pendaftaran PMB" pretitle="PMB" />
@endsection

@section('content')

    <x-tabler.card>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable
                id="table-pendaftaran"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'No. Pendaftaran'],
                    ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Nama Calon'],
                    ['data' => 'jalur.nama_jalur', 'name' => 'jalur.nama_jalur', 'title' => 'Jalur'],
                    ['data' => 'status_terkini', 'name' => 'status_terkini', 'title' => 'Status'],
                    ['data' => 'waktu_daftar', 'name' => 'waktu_daftar', 'title' => 'Tanggal Daftar'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :url="route('pmb.pendaftaran.data')"
            />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
