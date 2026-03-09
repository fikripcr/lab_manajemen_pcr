@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Bank Soal" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" data-modal-title="Tambah Mata Uji" data-url="{{ route('cbt.mata-uji.create') }}" text="Tambah Mata Uji" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable 
                id="table-mata-uji" 
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'nama_mata_uji', 'name' => 'nama_mata_uji', 'title' => 'Nama Mata Uji'],
                    ['data' => 'tipe', 'name' => 'tipe', 'title' => 'Tipe'],
                    ['data' => 'jumlah_soal', 'name' => 'jumlah_soal', 'title' => 'Jumlah Soal', 'orderable' => false, 'searchable' => false],
                    ['data' => 'kesulitan', 'name' => 'kesulitan', 'title' => 'Kesulitan', 'orderable' => false, 'searchable' => false],
                    ['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :url="route('cbt.mata-uji.data')"
            />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
