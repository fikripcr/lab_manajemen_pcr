@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Entitas Terkait
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Entitas</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.show', $rapat) }}" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali ke Detail Rapat
                </a>
                <a href="{{ route('pemutu.rapat.entitas.create', $rapat) }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Entitas
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Entitas Terkait</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="entitas-table" route="{{ route('pemutu.rapat.entitas.data', $rapat) }}" :columns="[
                [
                    'title' => 'Model',
                    'data' => 'model',
                    'name' => 'model',
                ],
                [
                    'title' => 'ID Entitas',
                    'data' => 'model_id',
                    'name' => 'model_id',
                ],
                [
                    'title' => 'Keterangan',
                    'data' => 'keterangan',
                    'name' => 'keterangan',
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
