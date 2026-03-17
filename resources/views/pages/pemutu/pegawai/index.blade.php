@extends('layouts.tabler.app')
@section('title', 'Pegawai')

@section('header')
<x-tabler.page-header title="Pegawai" pretitle="Master Data">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button type="import" class="btn-ghost-primary ajax-modal-btn" data-url="{{ route('pemutu.pegawai.import') }}" text="Impor Pegawai" />
            <x-tabler.button type="create" class="ajax-modal-btn" data-url="{{ route('pemutu.pegawai.create') }}" text="Tambah Pegawai" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                <div class="d-flex flex-wrap gap-2">
                    <x-tabler.datatable-page-length dataTableId="pegawai-table" />
                    <x-tabler.datatable-search dataTableId="pegawai-table" />
                </div>
                <div class="ms-auto">
                    <x-tabler.datatable-filter dataTableId="pegawai-table" type="button" target="#pegawai-filter-area" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <div class="collapse" id="pegawai-filter-area">
                <x-tabler.datatable-filter dataTableId="pegawai-table" type="bare">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-tabler.form-select name="org_unit_id" label="Unit / Area" placeholder="Semua Unit">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-select name="jenis" label="Jenis Pegawai" placeholder="Semua Jenis">
                                <option value="">Semua Jenis</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tendik">Tendik</option>
                            </x-tabler.form-select>
                        </div>
                    </div>
                </x-tabler.datatable-filter>
            </div>
            <x-tabler.datatable
                id="pegawai-table"
                :route="route('pemutu.pegawai.data')"
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['title' => 'Nama', 'data' => 'nama', 'name' => 'nama'],
                    ['title' => 'Email', 'data' => 'email', 'name' => 'email'],
                    ['title' => 'Unit', 'data' => 'org_unit_id', 'name' => 'orgUnit.name'],
                    ['title' => 'Jenis', 'data' => 'jenis', 'name' => 'jenis'],
                    ['title' => 'Linked', 'data' => 'user_id', 'name' => 'user_id', 'class' => 'text-center'],
                    ['title' => 'Actions', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :order="[[1, 'asc']]"
            />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
