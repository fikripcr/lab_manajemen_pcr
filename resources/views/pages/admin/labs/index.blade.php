@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tabel /</span> Manajemen Laboratorium</h4>
        <a href="{{ route('labs.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Lab Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Daftar Laboratorium</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-admin.datatable-page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            <x-admin.datatable-search-filter :dataTableId="'labs-table'" />
        </div>
        <div class="card-body">
            <x-admin.flash-message />

            <x-admin.datatable
                id="labs-table"
                route="{{ route('labs.data') }}"
                :columns="[
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false
                    ],
                    [
                        'title' => 'Nama',
                        'data' => 'name',
                        'name' => 'name'
                    ],
                    [
                        'title' => 'Lokasi',
                        'data' => 'location',
                        'name' => 'location'
                    ],
                    [
                        'title' => 'Kapasitas',
                        'data' => 'capacity',
                        'name' => 'capacity',
                    ],
                    [
                        'title' => 'Deskripsi',
                        'data' => 'description',
                        'name' => 'description',
                        'render' => 'function(data, type, row) {
                            return data && data.length > 50 ? data.substring(0, 50) + \'...\' : data;
                        }'
                    ],
                    [
                        'title' => 'Aksi',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false
                    ]
                ]"
            />
        </div>
    </div>
@endsection
