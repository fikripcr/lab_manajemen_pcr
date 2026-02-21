@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Mahasiswa" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.mahasiswa.create')" modal-title="Tambah Mahasiswa" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'mahasiswa-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'mahasiswa-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="mahasiswa-table" route="{{ route('lab.mahasiswa.data') }}" :columns="[
                [
                    'title' => 'NIM',
                    'data' => 'nim',
                    'name' => 'nim',
                ],
                [
                    'title' => 'Nama Mahasiswa',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Program Studi',
                    'data' => 'prodi_nama',
                    'name' => 'prodi_nama',
                ],
                [
                    'title' => 'User',
                    'data' => 'user_info',
                    'name' => 'user_info',
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" />
        </div>
    </div>
@endsection
