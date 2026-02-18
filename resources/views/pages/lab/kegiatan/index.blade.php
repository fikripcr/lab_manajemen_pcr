@extends('layouts.tabler.app')

@section('title', 'Peminjaman Lab')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Peminjaman Lab (Kegiatan)" pretitle="Perkuliahan">
        <x-slot:actions>
            <x-tabler.button type="create" href="{{ route('lab.kegiatan.create') }}" text="Ajukan Peminjaman" icon="bx bx-plus" />
        </x-slot:actions>
    </x-tabler.page-header>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-kegiatan"
                    route="{{ route('lab.kegiatan.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
                        ['data' => 'lab_nama', 'name' => 'lab.name', 'title' => 'Lab'],
                        ['data' => 'waktu', 'name' => 'tanggal', 'title' => 'Waktu'],
                        ['data' => 'penyelenggara.name', 'name' => 'penyelenggara.name', 'title' => 'Penyelenggara'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
