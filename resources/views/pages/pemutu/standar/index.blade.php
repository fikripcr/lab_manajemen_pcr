@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Indikator Standar & Performa" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button type="create" href="{{ route('pemutu.standar.create') }}" text="Tambah Indikator" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Daftar Indikator</h3>
            <div class="ms-auto d-flex flex-wrap gap-2">
                <x-tabler.datatable-page-length dataTableId="table-standar" />
                <x-tabler.datatable-search dataTableId="table-standar" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable-client
            id="table-standar"
            route="{{ route('pemutu.standar.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                ['data' => 'doksub_judul', 'name' => 'doksub_judul', 'title' => 'Dokumen / Sub'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'target_info', 'name' => 'target_info', 'title' => 'Target'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
            ]"
        />
    </div>
</div>
@endsection
