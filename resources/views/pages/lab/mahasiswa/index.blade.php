@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Master Mahasiswa
            </h2>
            <div class="text-muted mt-1">Master Data / Mahasiswa</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('lab.mahasiswa.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Mahasiswa
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'mahasiswa-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'mahasiswa-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="mahasiswa-table" route="{{ route('lab.mahasiswa.data') }}" :columns="[
                [
                    'title' => 'NIM',
                    'data' => 'nim',
                    'name' => 'nim',
                ],
                [
                    'title' => 'Nama Mahasiswa',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Prodi',
                    'data' => 'prodi',
                    'name' => 'prodi',
                ],
                [
                    'title' => 'Angkatan',
                    'data' => 'angkatan',
                    'name' => 'angkatan',
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" />
        </div>
    </div>
@endsection
