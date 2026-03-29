@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Kegiatan" pretitle="Kegiatan">
    <x-slot:actions>
        <x-tabler.button type="create" href="{{ route('Kegiatan.Kegiatans.create') }}" class="d-none d-sm-inline-block" text="Tambah Kegiatan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <x-tabler.card>
            <x-tabler.card-header>
                <div class="d-flex flex-wrap gap-2">
                    <div>
                        <x-tabler.datatable-page-length :dataTableId="'table-Kegiatans'" />
                    </div>
                    <div>
                        <x-tabler.datatable-search :dataTableId="'table-Kegiatans'" />
                    </div>
                </div>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-0">
                <x-tabler.datatable
                    id="table-Kegiatans"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'Kegiatan_id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'judul_Kegiatan', 'name' => 'judul_Kegiatan', 'title' => 'Judul Kegiatan'],
                        ['data' => 'jenis_Kegiatan', 'name' => 'jenis_Kegiatan', 'title' => 'Jenis'],
                        ['data' => 'tanggal_info', 'name' => 'tanggal_mulai', 'title' => 'Tanggal'],
                        ['data' => 'lokasi', 'name' => 'lokasi', 'title' => 'Lokasi'],
                        ['data' => 'pic.name', 'name' => 'pic.name', 'title' => 'PIC', 'defaultContent' => '-'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ]"
                    :route="route('Kegiatan.Kegiatans.data')"
                />
            </x-tabler.card-body>
        </x-tabler.card>
@endsection
