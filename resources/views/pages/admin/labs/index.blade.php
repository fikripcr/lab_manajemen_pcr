@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Manajemen Laboratorium" pretitle="Laboratorium">
    <x-slot:actions>
        <x-sys.button type="create" :href="route('labs.create')" text="Tambah Lab Baru" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="labs-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="labs-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-admin.flash-message />
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
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Deskripsi',
                        'data' => 'description',
                        'name' => 'description',
                    ],
                    [
                        'title' => 'Aksi',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-sys.datatable id="labs-table" :route="route('labs.data')" :columns="$columns" />
        </div>
    </div>
@endsection
