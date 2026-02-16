@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Master Jenis Dokumen</h2>
            </div>
            <div class="col-8 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Jenis Dokumen" data-url="{{ route('pmb.jenis-dokumen.create') }}">
                        <i class="ti ti-plus"></i> Tambah Jenis Dokumen
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
                    id="table-jenis-dokumen" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_dokumen', 'name' => 'nama_dokumen', 'title' => 'Nama Dokumen'],
                        ['data' => 'tipe_file', 'name' => 'tipe_file', 'title' => 'Tipe File'],
                        ['data' => 'max_size_kb', 'name' => 'max_size_kb', 'title' => 'Ukuran Maksimal'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.jenis-dokumen.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
