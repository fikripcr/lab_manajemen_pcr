@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen Personil" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.personil.create') }}" class="btn-primary d-none d-sm-inline-block" icon="ti ti-plus" text="Tambah Personil" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-personil"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'personil_id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                        ['data' => 'nip', 'name' => 'nip', 'title' => 'NIP/NIK'],
                        ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
                        ['data' => 'user_info', 'name' => 'user_info', 'title' => 'User Terkoneksi'],
                        ['data' => 'status_aktif', 'name' => 'status_aktif', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                    ]"
                    :route="route('shared.personil.paginate')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
