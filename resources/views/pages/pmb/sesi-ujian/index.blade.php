@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Sesi Ujian (CBT)" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" icon="ti ti-plus" text="Tambah Sesi"
            data-modal-target="#modalAction" data-modal-title="Tambah Sesi Ujian" data-url="{{ route('pmb.sesi-ujian.create') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-sesi-ujian"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nama_sesi', 'name' => 'nama_sesi', 'title' => 'Nama Sesi'],
                        ['data' => 'waktu_mulai', 'name' => 'waktu_mulai', 'title' => 'Mulai'],
                        ['data' => 'waktu_selesai', 'name' => 'waktu_selesai', 'title' => 'Selesai'],
                        ['data' => 'lokasi', 'name' => 'lokasi', 'title' => 'Lokasi'],
                        ['data' => 'kuota', 'name' => 'kuota', 'title' => 'Kuota'],
                            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ]"
                    :url="route('pmb.sesi-ujian.data')"
                />
            </div>
        </div>
@endsection
