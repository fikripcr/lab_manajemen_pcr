@extends('layouts.admin.app')

@section('title', 'Inventaris Lab: ' . $lab->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Inventaris Lab: {{ $lab->name }}</h4>
                    <a href="{{ route('labs.inventaris.create', encryptId($lab->lab_id)) }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Tambah Inventaris
                    </a>
                </div>
                <div class="card-body">
                    <x-flash-message />

                    <div class="table-responsive">
                        <table id="inventaris-table" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Inventaris</th>
                                    <th>Nama Alat</th>
                                    <th>Jenis Alat</th>
                                    <th>No Series</th>
                                    <th>Tanggal Penempatan</th>
                                    <th>Tanggal Penghapusan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.datatable.datatable-js', [
    'url' => route('labs.inventaris.data', encryptId($lab->lab_id)),
    'columns' => [
        ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
        ['data' => 'kode_inventaris', 'name' => 'kode_inventaris'],
        ['data' => 'nama_alat', 'name' => 'inventaris.nama_alat'],
        ['data' => 'jenis_alat', 'name' => 'inventaris.jenis_alat'],
        ['data' => 'no_series', 'name' => 'no_series'],
        ['data' => 'tanggal_penempatan', 'name' => 'tanggal_penempatan'],
        ['data' => 'tanggal_penghapusan', 'name' => 'tanggal_penghapusan'],
        ['data' => 'status', 'name' => 'status'],
        ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
    ]
])
@endsection