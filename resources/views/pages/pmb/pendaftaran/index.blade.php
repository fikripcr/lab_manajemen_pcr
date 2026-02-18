@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen Pendaftaran PMB" pretitle="PMB" />
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-pendaftaran" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'No. Pendaftaran'],
                        ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Nama Calon'],
                        ['data' => 'jalur.nama_jalur', 'name' => 'jalur.nama_jalur', 'title' => 'Jalur'],
                        ['data' => 'status_terkini', 'name' => 'status_terkini', 'title' => 'Status'],
                        ['data' => 'waktu_daftar', 'name' => 'waktu_daftar', 'title' => 'Tanggal Daftar'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.pendaftaran.paginate')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
