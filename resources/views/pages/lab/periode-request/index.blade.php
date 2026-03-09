@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Periode Request Software" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.periode-request.create')" modal-title="Tambah Periode Request Software" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'periode-request-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'periode-request-table'" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable
                id="periode-request-table" route="{{ route('lab.periode-request.data') }}" :columns="[
                [
                    'title' => 'Nama Periode',
                    'data' => 'nama_periode',
                    'name' => 'nama_periode',
                ],
                [
                    'title' => 'Semester',
                    'data' => 'semester',
                    'name' => 'semester.tahun_ajaran',
                ],
                [
                    'title' => 'Rentang Waktu',
                    'data' => 'date_range',
                    'name' => 'start_date',
                ],
                [
                    'title' => 'Status',
                    'data' => 'is_active',
                    'name' => 'is_active',
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
