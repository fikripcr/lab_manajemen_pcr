@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Personil" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.personil.create')" modal-title="Tambah Personil" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'personil-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'personil-table'" />
                </div>
                <div>
                    <x-tabler.datatable-filter :dataTableId="'personil-table'">
                        <div style="min-width: 180px;">
                            <x-tabler.form-select id="filter-unit" name="orgunit_id" placeholder="Filter Unit/Dept" class="mb-0">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->orgunit_id }}">{{ $unit->name_display ?? $unit->name }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </x-tabler.datatable-filter>
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
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
                    'class' => 'text-center',
                ],
            ]" />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
