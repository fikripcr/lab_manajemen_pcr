@extends('layouts.tabler.app')

@section('title', 'Permintaan Software')

@section('content')
    <x-tabler.page-header title="Daftar Permintaan Software" pretitle="Berkas">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.software-requests.create')" modal-title="Buat Request Software" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div>
                        <x-tabler.datatable-page-length dataTableId="table-software-requests" />
                    </div>
                    <div>
                        <x-tabler.datatable-search dataTableId="table-software-requests" />
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-software-requests"
                    route="{{ route('lab.software-requests.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'nama_software', 'name' => 'nama_software', 'title' => 'Nama Software'],
                        ['data' => 'dosen_name', 'name' => 'dosen.name', 'title' => 'Dosen'],
                        ['data' => 'mata_kuliah', 'name' => 'mata_kuliah', 'title' => 'Mata Kuliah', 'orderable' => false, 'searchable' => false],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                        ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '10%']
                    ]"
                    :order="[[5, 'desc']]"
                />
            </div>
        </div>
@endsection


