@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Verifikasi Berkas PMB</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-verifikasi-berkas" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'No. Pendaftaran'],
                        ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Nama Calon'],
                        ['data' => 'jalur.nama_jalur', 'name' => 'jalur.nama_jalur', 'title' => 'Jalur'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.verification.paginate-documents')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
