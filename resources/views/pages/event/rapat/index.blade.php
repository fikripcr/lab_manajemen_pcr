@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Manajemen Rapat" pretitle="Kegiatan / Rapat">
        <x-slot:actions>
            <x-tabler.button type="create" href="{{ route('Kegiatan.rapat.create') }}" text="Jadwalkan" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'rapat-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'rapat-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

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
        </div>
    </div>
@endsection
