@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Mata Kuliah" pretitle="Daftar MK">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.mata-kuliah.create')" modal-title="Tambah Mata Kuliah" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="mata-kuliah-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="mata-kuliah-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            <x-tabler.datatable
                id="mata-kuliah-table"
                :route="route('lab.mata-kuliah.data')"
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['title' => 'Kode MK', 'data' => 'kode_mk', 'name' => 'kode_mk'],
                    ['title' => 'Nama MK', 'data' => 'nama_mk', 'name' => 'nama_mk'],
                    ['title' => 'SKS', 'data' => 'sks', 'name' => 'sks', 'class' => 'text-center'],
                    ['title' => 'Actions', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :order="[[0, 'desc']]"
            />
        </div>
    </div>
@endsection
