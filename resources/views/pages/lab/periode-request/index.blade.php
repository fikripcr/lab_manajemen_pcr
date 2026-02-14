@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Periode Request Software
            </h2>
            <div class="text-muted mt-1">Software Requests / Periode Pengajuan</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('lab.periode-request.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Periode
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
                    <x-tabler.datatable-page-length :dataTableId="'periode-request-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'periode-request-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="periode-request-table" route="{{ route('lab.periode-request.data') }}" :columns="[
                [
                    'title' => 'Nama Periode',
                    'data' => 'nama_periode',
                    'name' => 'nama_periode',
                ],
                [
                    'title' => 'Semester',
                    'data' => 'semester',
                    'name' => 'semester.tahun_ajaran',
                ],
                [
                    'title' => 'Rentang Waktu',
                    'data' => 'date_range',
                    'name' => 'start_date',
                ],
                [
                    'title' => 'Status',
                    'data' => 'is_active',
                    'name' => 'is_active',
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
