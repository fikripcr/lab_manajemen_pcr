@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Jadwal Kuliah" pretitle="Perkuliahan">
    <x-slot:actions>
        <x-sys.button type="import" :href="route('jadwal.import.form')" text="Import Jadwal" />
        <x-sys.button type="create" :href="route('jadwal.create')" text="Create" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="jadwal-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="jadwal-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Hari',
                        'data' => 'tanggal',
                        'name' => 'tanggal'
                    ],
                    [
                        'title' => 'Waktu Mulai',
                        'data' => 'waktu_mulai',
                        'name' => 'waktu_mulai'
                    ],
                    [
                        'title' => 'Waktu Selesai',
                        'data' => 'waktu_selesai',
                        'name' => 'waktu_selesai'
                    ],
                    [
                        'title' => 'Mata Kuliah',
                        'data' => 'mata_kuliah_nama',
                        'name' => 'mata_kuliahs.nama_mk'
                    ],
                    [
                        'title' => 'Dosen',
                        'data' => 'dosen_nama',
                        'name' => 'users.name'
                    ],
                    [
                        'title' => 'Lab',
                        'data' => 'ruang_nama',
                        'name' => 'labs.name'
                    ],
                    [
                        'title' => 'Semester',
                        'data' => 'semester_nama_display',
                        'name' => 'semesters.tahun_ajaran'
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-sys.datatable id="jadwal-table" :route="route('jadwal.data')" :columns="$columns" :order="[[0, 'desc']]" />
        </div>
    </div>
@endsection
