@extends('layouts.admin.app')
@section('title', 'Data Indikator')

@section('header')
<x-tabler.page-header title="Data Indikator" pretitle="SPMI / Monitoring">
    <x-slot:actions>
        <a href="{{ route('pemtu.indikators.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-2"></i> Tambah Indikator
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-tabler.datatable
            id="indikator-table"
            route="{{ route('pemtu.indikators.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                ['data' => 'dokumen_judul', 'name' => 'dokSub.dokumen.judul', 'title' => 'Dokumen Induk'],
                ['data' => 'doksub_judul', 'name' => 'dokSub.judul', 'title' => 'Poin / Sub-Dok'],
                ['data' => 'labels', 'name' => 'labels.name', 'title' => 'Labels', 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '10%']
            ]"
        />
    </div>
</div>
@endsection
