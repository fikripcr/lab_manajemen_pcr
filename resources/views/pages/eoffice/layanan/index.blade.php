@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Transactions">
    <x-slot:actions>
        <a href="{{ route('eoffice.layanan.services') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Pengajuan
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Filter & Pencarian</h3>
    </div>
    <div class="card-body">
        <form id="filter-form" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Jenis Layanan</label>
                <select name="jenislayanan_id" class="form-select select2">
                    <option value="">Semua Jenis Layanan</option>
                    @foreach($jenisLayanans as $jl)
                        <option value="{{ $jl->jenislayanan_id }}">{{ $jl->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Diajukan">Diajukan</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Disposisi">Disposisi</option>
                    <option value="Direvisi">Butuh Revisi</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Rentang Tanggal</label>
                <input type="text" name="date_range" class="form-control" placeholder="Pilih Tanggal...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan</h3>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-layanan"
            route="{{ route('eoffice.layanan.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'no_layanan', 'name' => 'no_layanan', 'title' => 'No. Pengajuan', 'class' => 'fw-bold'],
                ['data' => 'jenis_layanan.nama_layanan', 'name' => 'jenisLayanan.nama_layanan', 'title' => 'Jenis Layanan'],
                ['data' => 'pengusul_nama', 'name' => 'pengusul_nama', 'title' => 'Pemohon'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tgl Diajukan', 'render' => 'function(data){ return moment(data).format(\'DD/MM/YYYY HH:mm\'); }'],
                ['data' => 'status_label', 'name' => 'latestStatus.status_layanan', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
            :filter-form="'#filter-form'"
        />
    </div>
</div>
@endsection
