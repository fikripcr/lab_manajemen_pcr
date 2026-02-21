@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Semester" pretitle="Perkuliahan">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.semesters.create-modal')" modal-title="Tambah Semester" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length dataTableId="semesters-table" />
                </div>
                <div>
                    <x-tabler.datatable-search dataTableId="semesters-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            <x-tabler.datatable 
                id="semesters-table" 
                :route="route('lab.semesters.data')" 
                :columns="[
                    ['title' => '#', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                    ['title' => 'Tahun Ajaran', 'data' => 'tahun_ajaran', 'name' => 'tahun_ajaran'],
                    ['title' => 'Semester', 'data' => 'semester', 'name' => 'semester'],
                    ['title' => 'Start Date', 'data' => 'start_date', 'name' => 'start_date'],
                    ['title' => 'End Date', 'data' => 'end_date', 'name' => 'end_date'],
                    ['title' => 'Status', 'data' => 'is_active', 'name' => 'is_active'],
                    ['title' => 'Actions', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
                ]" 
                :order="[[0, 'desc']]" 
            />
        </div>
    </div>
@endsection

