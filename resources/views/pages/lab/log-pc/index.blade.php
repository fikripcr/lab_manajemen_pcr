@extends('layouts.tabler.app')

@section('title', 'Log Penggunaan PC')

@section('content')
    <x-tabler.page-header title="Log Penggunaan PC" pretitle="Monitoring">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.log-pc.create')" modal-title="Tambah Log Penggunaan PC" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div>
                        <x-tabler.datatable-page-length dataTableId="table-log-pc" />
                    </div>
                    <div>
                        <x-tabler.datatable-search dataTableId="table-log-pc" />
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-log-pc"
                    route="{{ route('lab.log-pc.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'mahasiswa', 'name' => 'user.name', 'title' => 'Mahasiswa'],
                        ['data' => 'waktu', 'name' => 'waktu_isi', 'title' => 'Waktu', 'class' => 'text-center'],
                        ['data' => 'pc_info', 'name' => 'lab.name', 'title' => 'PC Info'],
                        ['data' => 'kondisi', 'name' => 'status_pc', 'title' => 'Kondisi & Catatan']
                    ]"
                    :order="[[2, 'desc']]"
                />
            </div>
        </div>
@endsection


