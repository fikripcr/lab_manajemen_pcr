@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Mata Kuliah" pretitle="Perkuliahan">
    <x-slot:actions>
        <x-sys.button type="create" :href="route('mata-kuliah.create')" text="Create" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="mata-kuliah-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="mata-kuliah-table" />
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
                        'title' => 'Kode MK',
                        'data' => 'kode_mk',
                        'name' => 'kode_mk'
                    ],
                    [
                        'title' => 'Nama MK',
                        'data' => 'nama_mk',
                        'name' => 'nama_mk'
                    ],
                    [
                        'title' => 'SKS',
                        'data' => 'sks',
                        'name' => 'sks',
                        'className' => 'text-center'
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
            <x-sys.datatable id="mata-kuliah-table" :route="route('mata-kuliah.data')" :columns="$columns" :order="[[0, 'desc']]" />
        </div>
    </div>
@endsection
