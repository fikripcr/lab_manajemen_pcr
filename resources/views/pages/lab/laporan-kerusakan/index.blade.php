@extends('layouts.tabler.app')

@section('title', 'Laporan Kerusakan')

@section('content')
    <x-tabler.page-header title="Laporan Kerusakan" pretitle="Berkas">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.laporan-kerusakan.create')" modal-title="Buat Laporan Kerusakan" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div>
                        <x-tabler.datatable-page-length dataTableId="table-laporan" />
                    </div>
                    <div>
                        <x-tabler.datatable-search dataTableId="table-laporan" />
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-laporan"
                    route="{{ route('lab.laporan-kerusakan.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'alat_info', 'name' => 'inventaris.nama_alat', 'title' => 'Alat / Inventaris'],
                        ['data' => 'pelapor', 'name' => 'createdBy.name', 'title' => 'Pelapor'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                        ['data' => 'tanggal', 'name' => 'created_at', 'title' => 'Tanggal', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
                    ]"
                />
            </div>
        </div>
@endsection


