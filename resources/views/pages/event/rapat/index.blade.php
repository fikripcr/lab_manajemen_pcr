@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Manajemen Rapat" pretitle="Kegiatan / Rapat">
        <x-slot:actions>
            <x-tabler.button type="button" class="ajax-modal-btn" data-url="{{ route('Kegiatan.rapat.create') }}" data-modal-size="modal-xl" data-modal-title="Jadwalkan Rapat" icon="ti ti-plus" text="Jadwalkan" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'rapat-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'rapat-table'" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable
                id="rapat-table" route="{{ route('Kegiatan.rapat.data') }}" :columns="[
                [
                    'title' => 'Kegiatan & Tempat',
                    'data'  => 'rapat_info',
                    'name'  => 'judul_kegiatan',
                ],
                [
                    'title' => 'Waktu & Durasi',
                    'data'  => 'waktu_info',
                    'name'  => 'tgl_rapat',
                ],
                [
                    'title' => 'Pejabat Rapat',
                    'data'  => 'pejabat_info',
                    'name'  => 'ketua_user.name',
                ],
                [
                    'title' => 'Actions',
                    'data'  => 'action',
                    'name'  => 'action',
                    'orderable' => false,
                    'searchable' => false,
                    'class' => 'text-center',
                ],
            ]" />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
