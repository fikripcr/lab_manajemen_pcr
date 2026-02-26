@extends('layouts.tabler.app')
@section('title', 'Data Indikator')

@section('header')
<x-tabler.page-header title="Data Indikator" pretitle="SPMI / Monitoring">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.indikators.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Indikator" />
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
                        <x-tabler.form-select name="dokumen_id" placeholder="Filter Dokumen" :options="$dokumens" type="select2" />
                    </div>
                    <div>
                        <x-tabler.form-select name="type" placeholder="Filter Tipe" :options="$types" />
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
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                ['data' => 'tipe', 'name' => 'type', 'title' => 'Tipe', 'width' => '10%'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                ['data' => 'dokumen_judul', 'name' => 'dokSubs.dokumen.judul', 'title' => 'Dokumen Induk', 'searchable' => false, 'orderable' => false],
                ['data' => 'doksub_judul', 'name' => 'dokSubs.judul', 'title' => 'Poin / Sub-Dok', 'searchable' => false, 'orderable' => false],
                ['data' => 'labels', 'name' => 'labels', 'title' => 'Labels', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
        />
    </div>
</div>
@endsection
