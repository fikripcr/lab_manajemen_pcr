@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Master Program Studi PMB</h2>
            </div>
            <div class="col-8 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Prodi" data-url="{{ route('pmb.prodi.create') }}">
                        <i class="ti ti-plus"></i> Tambah Prodi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-prodi" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'kode_prodi', 'name' => 'kode_prodi', 'title' => 'Kode'],
                        ['data' => 'nama_prodi', 'name' => 'nama_prodi', 'title' => 'Nama Prodi'],
                        ['data' => 'fakultas', 'name' => 'fakultas', 'title' => 'Fakultas'],
                        ['data' => 'kuota_umum', 'name' => 'kuota_umum', 'title' => 'Kuota'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.prodi.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
