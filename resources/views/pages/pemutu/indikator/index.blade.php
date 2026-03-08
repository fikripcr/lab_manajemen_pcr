@extends('layouts.tabler.app')
@section('title', 'Data Indikator')

@section('header')
<x-tabler.page-header title="Data Indikator" pretitle="SPMI / Monitoring">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.indikator.create', ['type' => $activeType]) }}"  type="create" text="{{ $activeType === 'performa' ? 'Indikator KPI' : 'Indikator ' . ucfirst($activeType) }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-body class="border-bottom p-2 bg-transparent">
        <ul class="nav nav-pills nav-fill gap-2 text-uppercase fw-bold">
            @foreach($types as $key => $label)
            <li class="nav-item">
                <a href="{{ route('pemutu.indikator.index', ['type' => $key]) }}" 
                   class="nav-link py-2 {{ $activeType == $key ? 'active fw-bold shadow-sm' : '' }}">
                   Indikator {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </x-tabler.card-body>
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <x-tabler.datatable-page-length :dataTableId="'indikator-table'" />
            <x-tabler.datatable-search :dataTableId="'indikator-table'" />
            <x-tabler.datatable-filter :dataTableId="'indikator-table'">
                <div>
                    <x-tabler.form-select name="periode" placeholder="Filter Tahun" :options="$periodes" />
                </div>
                <div>
                    <x-tabler.form-select name="dokumen_id" placeholder="Filter Dokumen" :options="$dokumens" type="select2" />
                </div>
            </x-tabler.datatable-filter>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0 table-responsive">
        <x-tabler.datatable
            id="indikator-table"
            route="{{ route('pemutu.indikator.data', ['type' => $activeType]) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                ['data' => 'tipe', 'name' => 'type', 'title' => 'Tipe', 'width' => '10%'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Dokumen Induk', 'searchable' => false, 'orderable' => false],
                ['data' => 'labels', 'name' => 'labels', 'title' => 'Labels', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
