@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen Laboratorium" pretitle="Laboratorium">
    <x-slot:actions>
        <x-tabler.button type="create" :href="route('lab.labs.create')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="labs-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="labs-table" />
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
            <x-tabler.datatable id="labs-table" :route="route('lab.labs.data')" :columns="$columns" />
        </div>
    </div>
@endsection
