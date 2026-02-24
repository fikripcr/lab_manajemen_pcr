@extends('layouts.tabler.app')

@section('title', 'Peminjaman Lab')

@section('header')
<x-tabler.page-header title="Peminjaman Lab (Kegiatan)" pretitle="Perkuliahan">
    <x-slot:actions>
        <x-tabler.button type="create" text="Ajukan Peminjaman" class="ajax-modal-btn" :modal-url="route('lab.kegiatan.create')" modal-title="Ajukan Peminjaman Lab" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-kegiatan"
                    route="{{ route('lab.kegiatan.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
                        ['data' => 'lab_nama', 'name' => 'lab.name', 'title' => 'Lab'],
                        ['data' => 'waktu', 'name' => 'tanggal', 'title' => 'Waktu'],
                        ['data' => 'penyelenggara.name', 'name' => 'penyelenggara.name', 'title' => 'Penyelenggara'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                />
            </div>
        </div>

@endsection
