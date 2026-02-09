@extends('layouts.admin.app')
@section('title', 'Data Indikator')

@section('header')
<x-tabler.page-header title="Data Indikator" pretitle="SPMI / Monitoring">
    <x-slot:actions>
        <a href="{{ route('pemutu.indikators.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-2"></i> Tambah Indikator
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'indikator-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'indikator-table'" />
            </div>
            <div>
                <x-tabler.datatable-filter :dataTableId="'indikator-table'">
                    <div>
                        <x-form.select2 name="dokumen_id" placeholder="Filter Dokumen" :options="$dokumens" />
                    </div>
                </x-tabler.datatable-filter>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable
            id="indikator-table"
            route="{{ route('pemutu.indikators.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                ['data' => 'dokumen_judul', 'name' => 'dokSub.dokumen.judul', 'title' => 'Dokumen Induk'],
                ['data' => 'doksub_judul', 'name' => 'dokSub.judul', 'title' => 'Poin / Sub-Dok'],
                ['data' => 'labels', 'name' => 'labels', 'title' => 'Labels', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '10%']
            ]"
        />
    </div>
</div>
@endsection
