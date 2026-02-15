@extends('layouts.admin.app')

@section('content')
@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.periode-spmis.create') }}" style="primary" icon="ti ti-plus" text="Tambah Periode" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-periode-spmi"
            route="{{ route('pemutu.periode-spmis.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                ['data' => 'periode', 'name' => 'periode', 'title' => 'Tahun', 'class' => 'font-weight-bold'],
                ['data' => 'jenis_periode', 'name' => 'jenis_periode', 'title' => 'Jenis'],
                ['data' => 'penetapan_awal', 'name' => 'penetapan_awal', 'title' => 'Penetapan'],
                ['data' => 'ami_awal', 'name' => 'ami_awal', 'title' => 'AMI (Evaluasi)'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
            ]"
        />
    </div>
</div>
@endsection
@endsection
