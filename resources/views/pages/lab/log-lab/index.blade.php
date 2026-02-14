@extends('layouts.admin.app')

@section('title', 'Log Penggunaan Lab (Guest)')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Log Penggunaan Lab
                </h2>
                <div class="text-muted mt-1">
                    Buku tamu / log peserta kegiatan lab
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.log-lab.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Isi Log Tamu
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-log-lab"
                    route="{{ route('lab.log-lab.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'waktu', 'name' => 'waktu_isi', 'title' => 'Waktu'],
                        ['data' => 'kegiatan', 'name' => 'kegiatan.nama_kegiatan', 'title' => 'Kegiatan / Event'],
                        ['data' => 'lab_nama', 'name' => 'lab.name', 'title' => 'Lab'],
                        ['data' => 'peserta', 'name' => 'nama_peserta', 'title' => 'Nama Peserta'],
                        ['data' => 'kondisi', 'name' => 'kondisi', 'title' => 'Kondisi PC/Alat']
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
