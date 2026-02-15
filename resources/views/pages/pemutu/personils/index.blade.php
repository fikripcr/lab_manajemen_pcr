@extends('layouts.admin.app')
@section('title', 'Personil')

@section('header')
<x-tabler.page-header title="Personil" pretitle="Master Data">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button type="import" class="btn-ghost-primary ajax-modal-btn" data-url="{{ route('pemutu.personils.import') }}" modal-title="Impor Personil" />
            <x-tabler.button type="create" class="ajax-modal-btn" data-url="{{ route('pemutu.personils.create') }}" modal-title="Tambah Personil" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="personils-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="personils-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            <x-tabler.datatable 
                id="personils-table" 
                :route="route('pemutu.personils.data')" 
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                    ['title' => 'Nama', 'data' => 'nama', 'name' => 'nama'],
                    ['title' => 'Email', 'data' => 'email', 'name' => 'email'],
                    ['title' => 'Unit', 'data' => 'org_unit_id', 'name' => 'orgUnit.name'],
                    ['title' => 'Jenis', 'data' => 'jenis', 'name' => 'jenis'],
                    ['title' => 'Linked', 'data' => 'user_id', 'name' => 'user_id', 'className' => 'text-center'],
                    ['title' => 'Actions', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
                ]" 
                :order="[[1, 'asc']]" 
            />
        </div>
    </div>
@endsection
