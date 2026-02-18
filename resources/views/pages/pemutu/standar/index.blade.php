@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Indikator Standar & Performa" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.standar.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Indikator" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <x-tabler.datatable-client 
        id="table-standar" 
        route="{{ route('pemutu.standar.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
            ['data' => 'doksub_judul', 'name' => 'doksub_judul', 'title' => 'Dokumen / Sub'],
            ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
            ['data' => 'target_info', 'name' => 'target_info', 'title' => 'Target'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
        ]"
    />

</div>
@endsection
