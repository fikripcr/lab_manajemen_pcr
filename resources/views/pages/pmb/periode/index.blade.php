@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Master Periode PMB</h2>
            </div>
            <div class="col-8 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Periode" data-url="{{ route('pmb.periode.create') }}">
                        <i class="ti ti-plus"></i> Tambah Periode
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
                    id="table-periode" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_periode', 'name' => 'nama_periode', 'title' => 'Nama Periode'],
                        ['data' => 'tanggal_mulai', 'name' => 'tanggal_mulai', 'title' => 'Mulai'],
                        ['data' => 'tanggal_selesai', 'name' => 'tanggal_selesai', 'title' => 'Selesai'],
                        ['data' => 'is_aktif', 'name' => 'is_aktif', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.periode.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
