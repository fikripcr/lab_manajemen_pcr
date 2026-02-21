@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Mahasiswa" pretitle="Shared Data" />
@endsection

@section('content')
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-mahasiswa"
                    route="{{ route('shared.mahasiswa.index') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'mahasiswa_id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nim', 'name' => 'nim', 'title' => 'NIM'],
                        ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                        ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                        ['data' => 'prodi_nama', 'name' => 'prodi_nama', 'title' => 'Prodi'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </div>
@endsection
