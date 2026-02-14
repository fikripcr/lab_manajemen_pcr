@extends('layouts.admin.app')

@section('title', 'Peminjaman Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Peminjaman Lab (Kegiatan)
                </h2>
                <div class="text-muted mt-1">
                    Daftar kegiatan dan peminjaman lab
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.kegiatan.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Ajukan Peminjaman
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-kegiatan"
                    route="{{ route('lab.kegiatan.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
                        ['data' => 'lab_nama', 'name' => 'lab.name', 'title' => 'Lab'],
                        ['data' => 'waktu', 'name' => 'tanggal', 'title' => 'Waktu'],
                        ['data' => 'penyelenggara.name', 'name' => 'penyelenggara.name', 'title' => 'Penyelenggara'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
