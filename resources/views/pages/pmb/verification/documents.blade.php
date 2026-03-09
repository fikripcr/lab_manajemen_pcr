@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Verifikasi Berkas PMB" pretitle="PMB" />
@endsection

@section('content')

    <x-tabler.card>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable
                id="table-verifikasi-berkas"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'No. Pendaftaran'],
                    ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Nama Calon'],
                    ['data' => 'jalur.nama_jalur', 'name' => 'jalur.nama_jalur', 'title' => 'Jalur'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :url="route('pmb.verification.documents.data')"
            />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
