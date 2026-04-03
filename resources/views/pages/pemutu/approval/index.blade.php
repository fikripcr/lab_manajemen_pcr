@extends('layouts.tabler.app')

@section('title', 'Approval Dokumen')

@section('header')
<x-tabler.page-header title="Approval Dokumen" pretitle="Pemutu">
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header>
                <div class="ms-auto d-flex gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-approval" />
                    <x-tabler.datatable-search dataTableId="table-approval" />
                    <x-tabler.datatable-filter dataTableId="table-approval" type="button" target="#table-approval-filter-area" />
                </div>
            </x-tabler.card-header>

            <div class="collapse" id="table-approval-filter-area">
                <x-tabler.datatable-filter dataTableId="table-approval" type="bare">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-tabler.form-select name="status" id="status" label="Status Approval" placeholder="">
                                <option value="all">Semua Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </x-tabler.form-select>
                        </div>
                    </div>
                </x-tabler.datatable-filter>
            </div>
            
            <div class="table-responsive">
                <x-tabler.datatable
                    id="table-approval"
                    route="{{ route('pemutu.approval.index') }}"
                    :columns="[
                        ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Masuk', 'width' => '15%'],
                        ['data' => 'tipe_approval', 'name' => 'model', 'title' => 'Tipe Dokumen', 'width' => '15%'],
                        ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Judul Dokumen', 'orderable' => false, 'searchable' => false],
                        ['data' => 'status_badge', 'name' => 'status', 'title' => 'Status', 'width' => '10%', 'class' => 'text-center'],
                        ['data' => 'oleh', 'name' => 'pejabat', 'title' => 'Oleh', 'width' => '15%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </x-tabler.card>
    </div>
</div>
@endsection
