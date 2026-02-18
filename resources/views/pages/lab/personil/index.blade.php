@extends('layouts.tabler.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Master Personil
            </h2>
            <div class="text-muted mt-1">Master Data / Personil</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="create" href="{{ route('lab.personil.create') }}" text="Tambah Personil" />
            </div>
        </div>
    </div>
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
