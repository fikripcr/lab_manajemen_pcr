@extends('layouts.admin.app')

@section('title', 'Surat Bebas Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Surat Bebas Lab
                </h2>
                <div class="text-muted mt-1">
                    Pengajuan surat bebas lab untuk yudisium/wisuda
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.surat-bebas.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Ajukan Surat
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-surat-bebas"
                    route="{{ route('lab.surat-bebas.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'mahasiswa', 'name' => 'student.name', 'title' => 'Mahasiswa'],
                        ['data' => 'tanggal', 'name' => 'created_at', 'title' => 'Tanggal Pengajuan'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
