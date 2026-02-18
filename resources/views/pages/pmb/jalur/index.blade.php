@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Master Jalur PMB</h2>
            </div>
            <div class="col-8 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block ajax-modal-btn" icon="ti ti-plus" text="Tambah Jalur" 
                        data-modal-target="#modalAction" data-modal-title="Tambah Jalur" data-url="{{ route('pmb.jalur.create') }}" />
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
                    id="table-jalur" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_jalur', 'name' => 'nama_jalur', 'title' => 'Nama Jalur'],
                        ['data' => 'biaya_pendaftaran', 'name' => 'biaya_pendaftaran', 'title' => 'Biaya Pendaftaran'],
                        ['data' => 'is_aktif', 'name' => 'is_aktif', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.jalur.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
