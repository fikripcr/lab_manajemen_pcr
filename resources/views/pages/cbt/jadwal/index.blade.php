@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Penjadwalan Ujian (CBT)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-action" data-title="Tambah Jadwal Ujian" data-url="{{ route('cbt.jadwal.create') }}">
                    <i class="ti ti-plus"></i> Tambah Jadwal
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
                    id="table-jadwal" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
                        ['data' => 'paket.nama_paket', 'name' => 'paket.nama_paket', 'title' => 'Paket Ujian'],
                        ['data' => 'waktu_mulai', 'name' => 'waktu_mulai', 'title' => 'Mulai'],
                        ['data' => 'waktu_selesai', 'name' => 'waktu_selesai', 'title' => 'Selesai'],
                        ['data' => 'token_ujian', 'name' => 'token_ujian', 'title' => 'Token', 'width' => '100px'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('cbt.jadwal.paginate')"
                />
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-action" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="modal-content-body"></div>
    </div>
</div>
@endsection
