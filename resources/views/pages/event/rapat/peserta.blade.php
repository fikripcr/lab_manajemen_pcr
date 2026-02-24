@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Peserta Rapat" pretitle="Kegiatan / Meeting / Peserta">
        <x-slot:actions>
            <x-tabler.button href="{{ route('Kegiatan.rapat.show', $rapat) }}" icon="ti ti-plus" text="Tambah Peserta" class="d-none d-sm-inline-block" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peserta Rapat</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="peserta-table" route="{{ route('Kegiatan.rapat.peserta.data', $rapat) }}" :columns="[
                [
                    'title' => 'NIP/NIK',
                    'data' => 'nip',
                    'name' => 'nip',
                ],
                [
                    'title' => 'Nama Peserta',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Jabatan',
                    'data' => 'jabatan',
                    'name' => 'jabatan',
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
