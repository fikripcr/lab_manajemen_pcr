@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Peserta Rapat" pretitle="Kegiatan / Meeting / Peserta">
        <x-slot:actions>
            <x-tabler.button type="create" href="{{ route('Kegiatan.rapat.show', $rapat) }}" text="Tambah Peserta" class="d-none d-sm-inline-block" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header title="Daftar Peserta" />
        <x-tabler.card-body class="p-0">
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
