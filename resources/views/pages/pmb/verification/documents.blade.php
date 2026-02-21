@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Verifikasi Berkas PMB" pretitle="PMB" />
@endsection

@section('content')

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-verifikasi-berkas" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'No. Pendaftaran'],
                        ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Nama Calon'],
                        ['data' => 'jalur.nama_jalur', 'name' => 'jalur.nama_jalur', 'title' => 'Jalur'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.verification.paginate-documents')"
                />
            </div>
        </div>
@endsection
