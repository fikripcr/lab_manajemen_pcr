@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Peserta Rapat
            </h2>
            <div class="text-muted mt-1">Kegiatan / Meeting / Peserta</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('Kegiatan.rapat.show', $rapat) }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Peserta
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peserta Rapat</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="peserta-table" route="{{ route('Kegiatan.rapat.peserta.data', $rapat) }}" :columns="[
                [
                    'title' => 'NIP/NIK',
                    'data' => 'nip',
                    'name' => 'nip',
                ],
                [
                    'title' => 'Nama Peserta',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Jabatan',
                    'data' => 'jabatan',
                    'name' => 'jabatan',
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
