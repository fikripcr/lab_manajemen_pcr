@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Mahasiswa" pretitle="Shared Data" />
@endsection

@section('content')
        <x-tabler.card>
            <x-tabler.card-header class="border-bottom py-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <div>
                        <x-tabler.datatable-page-length :dataTableId="'table-mahasiswa'" />
                    </div>
                    <div>
                        <x-tabler.datatable-search :dataTableId="'table-mahasiswa'" />
                    </div>
                </div>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-0">
                <x-tabler.datatable
                    id="table-mahasiswa"
                    route="{{ route('shared.mahasiswa.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'mahasiswa_id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nim', 'name' => 'nim', 'title' => 'NIM'],
                        ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                        ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                        ['data' => 'prodi_nama', 'name' => 'prodi_nama', 'title' => 'Prodi'],
                            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                />
            </x-tabler.card-body>
        </x-tabler.card>
@endsection
