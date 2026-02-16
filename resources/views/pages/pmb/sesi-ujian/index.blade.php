@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Manajemen Sesi Ujian (CBT)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Sesi Ujian" data-url="{{ route('pmb.sesi-ujian.create') }}">
                    <i class="ti ti-plus"></i> Tambah Sesi
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-sesi-ujian" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_sesi', 'name' => 'nama_sesi', 'title' => 'Nama Sesi'],
                        ['data' => 'waktu_mulai', 'name' => 'waktu_mulai', 'title' => 'Mulai'],
                        ['data' => 'waktu_selesai', 'name' => 'waktu_selesai', 'title' => 'Selesai'],
                        ['data' => 'lokasi', 'name' => 'lokasi', 'title' => 'Lokasi'],
                        ['data' => 'kuota', 'name' => 'kuota', 'title' => 'Kuota'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.sesi-ujian.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
