@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            Data Mesin Presensi
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
             <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.att-device.create') }}" data-modal-title="Tambah Mesin Presensi" />
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'att-device-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'att-device-table'" />
            </div>
        </div>
    </div>
    <div class="card-body">
         <x-tabler.flash-message />
        <x-tabler.datatable 
            id="att-device-table"
            route="{{ route('hr.att-device.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                ['data' => 'name', 'name' => 'name', 'title' => 'Nama Mesin'],
                ['data' => 'sn', 'name' => 'sn', 'title' => 'Serial Number'],
                ['data' => 'ip', 'name' => 'ip', 'title' => 'IP Address'],
                ['data' => 'port', 'name' => 'port', 'title' => 'Port'],
                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
            ]"
        />
    </div>
</div>
@endsection
