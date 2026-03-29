@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Paket Ujian" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Paket Ujian" data-url="{{ route('cbt.paket.create') }}" text="Tambah Paket" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header class="border-bottom">
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                <h3 class="card-title mb-0">Daftar Paket Ujian</h3>
                <div class="d-flex flex-wrap gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-paket" />
                    <x-tabler.datatable-search dataTableId="table-paket" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable 
                id="table-paket" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nama_paket', 'name' => 'nama_paket', 'title' => 'Nama Paket'],
                        ['data' => 'tipe_paket', 'name' => 'tipe_paket', 'title' => 'Tipe'],
                        ['data' => 'total_soal', 'name' => 'total_soal', 'title' => 'Jml Soal'],
                        ['data' => 'total_durasi_menit', 'name' => 'total_durasi_menit', 'title' => 'Durasi (Menit)', 'class' => 'text-center'],
                        ['data' => 'pembuat.name', 'name' => 'pembuat.name', 'title' => 'Dibuat Oleh', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                    :url="route('cbt.paket.data')"
                />
            </x-tabler.card-body>
    </x-tabler.card>
@endsection

