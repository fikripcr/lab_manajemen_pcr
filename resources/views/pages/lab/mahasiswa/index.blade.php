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
                <x-tabler.button type="create" href="{{ route('lab.mahasiswa.create') }}" text="Tambah Mahasiswa" />
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
                    'title' => 'Program Studi',
                    'data' => 'prodi_nama',
                    'name' => 'prodi_nama',
                ],
                [
                    'title' => 'User',
                    'data' => 'user_info',
                    'name' => 'user_info',
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
