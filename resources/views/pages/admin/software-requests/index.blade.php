@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Software Request Management" pretitle="Tables" />
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="software-requests-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="software-requests-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Nama Software',
                        'data' => 'nama_software',
                        'name' => 'nama_software'
                    ],
                    [
                        'title' => 'Nama Dosen',
                        'data' => 'dosen_name',
                        'name' => 'dosen.name',
                    ],
                    [
                        'title' => 'Mata Kuliah',
                        'data' => 'mata_kuliah',
                        'name' => 'mata_kuliah',
                        'orderable' => false,
                        'searchable' => false,
                    ],
                    [
                        'title' => 'Status',
                        'data' => 'status',
                        'name' => 'status'
                    ],
                    [
                        'title' => 'Dibuat Pada',
                        'data' => 'created_at',
                        'name' => 'created_at',
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-sys.datatable id="software-requests-table" :route="route('software-requests.data')" :columns="$columns" :order="[[0, 'desc']]" />
        </div>
    </div>
@endsection
