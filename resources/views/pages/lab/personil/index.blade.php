@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Personil" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.personil.create')" modal-title="Tambah Personil" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'personil-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'personil-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="personil-table" route="{{ route('lab.personil.data') }}" :columns="[
                [
                    'title' => 'NIP/NIK',
                    'data' => 'nip',
                    'name' => 'nip',
                ],
                [
                    'title' => 'Nama Personil',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Posisi',
                    'data' => 'posisi',
                    'name' => 'posisi',
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
