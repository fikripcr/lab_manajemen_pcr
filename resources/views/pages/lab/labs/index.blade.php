@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Laboratorium" pretitle="Laboratorium">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.labs.create')" modal-title="Tambah Laboratorium" data-modal-size="modal-xl" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card class="overflow-hidden">
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="labs-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="labs-table" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'class' => 'text-center'
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
                        'class' => 'text-center'
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
                        'class' => 'text-center'
                    ]
                ];
            @endphp
            <x-tabler.datatable id="labs-table" :route="route('lab.labs.data')" :columns="$columns" />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
