@extends('layouts.tabler.app')
@section('title', 'Isi Evaluasi Diri')

@section('header')
<x-tabler.page-header title="Isi Evaluasi Diri" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <a href="{{ route('pemutu.evaluasi-diri.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-2"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
@if($unit)
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'table-ed'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'table-ed'" />
            </div>
            <div>
                <x-tabler.datatable-filter :dataTableId="'table-ed'">
                    <div>
                        <x-tabler.form-select name="unit_id" id="unit-filter" placeholder="Filter Area / Unit" :options="$userUnits->pluck('name', 'orgunit_id')" :selected="$selectedUnitId" type="select2" />
                    </div>
                </x-tabler.datatable-filter>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
            <x-tabler.datatable
                id="table-ed"
                route="{{ route('pemutu.evaluasi-diri.data', $periode->encrypted_periodespmi_id) }}"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                    ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%','class' => 'text-left' ],
                    ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian', 'width' => '15%','class' => 'text-left'  ],
                    ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'width' => '30%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ]"
            />
    </div>



@else
<div class="card">
    <div class="card-body">
        <x-tabler.empty-state
            title="Tidak Terdaftar di Tim Mutu"
            text="{{ session('warning') ?? 'Anda tidak terdaftar dalam Tim Mutu atau Unit manapun untuk periode ini.' }}"
            icon="ti ti-lock-access"
        />
    </div>
</div>
@endif
@endsection
