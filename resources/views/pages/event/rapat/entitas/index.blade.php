@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Entitas Terkait" pretitle="Kegiatan / Meeting / Entitas">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('Kegiatan.rapat.show', $rapat) }}" />
            <x-tabler.button type="create" href="{{ route('Kegiatan.rapat.entitas.create', $rapat) }}" text="Tambah Entitas" class="d-none d-sm-inline-block" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <div class="card-header">
            <h3 class="card-title">Daftar Entitas Terkait</h3>
        </div>
        <div class="card-body">

            <x-tabler.datatable
                id="entitas-table" route="{{ route('Kegiatan.rapat.entitas.data', $rapat) }}" :columns="[
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
