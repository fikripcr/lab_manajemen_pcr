@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Shared Data" />
@endsection

@section('content')
        <x-tabler.card>
            <x-tabler.card-body class="p-0">
                <x-tabler.datatable
                    id="table-pegawai"
                    route="{{ route('hr.pegawai.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'pegawai_id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nip', 'name' => 'nip', 'title' => 'NIP'],
                        ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                        ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                        ['data' => 'unit_kerja.name', 'name' => 'unitKerja.name', 'title' => 'Unit Kerja', 'defaultContent' => '-'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                />
            </x-tabler.card-body>
        </x-tabler.card>
@endsection
