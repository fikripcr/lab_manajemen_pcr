@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Transactions">
    <x-slot:actions>
        <x-tabler.button type="create" :href="route('eoffice.layanan.services')" />
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
                <x-tabler.form-select name="jenislayanan_id" label="Jenis Layanan" class="select2">
                    <option value="">Semua Jenis Layanan</option>
                    @foreach($jenisLayanans as $jl)
                        <option value="{{ $jl->jenislayanan_id }}">{{ $jl->nama_layanan }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-3">
                <x-tabler.form-select name="status" label="Status">
                    <option value="">Semua Status</option>
                    <option value="Diajukan">Diajukan</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Disposisi">Disposisi</option>
                    <option value="Direvisi">Butuh Revisi</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </x-tabler.form-select>
            </div>
            <div class="col-md-3">
                <x-tabler.form-input name="date_range" label="Rentang Tanggal" placeholder="Pilih Tanggal..." />
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="w-100">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <x-tabler.button type="submit" icon="ti ti-filter" text="Filter" class="w-100" />
                </div>
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
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tgl Diajukan'],
                ['data' => 'status_label', 'name' => 'latestStatus.status_layanan', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
            :filter-form="'#filter-form'"
        />
    </div>
</div>
@endsection
