@extends('layouts.tabler.app')

@section('title', 'Daftar Assignment PC')

@section('content')
    <div class="row">
        <div class="col-12">
<x-tabler.page-header :title="'Assignment PC: ' . ($jadwal->mataKuliah->nama_mk ?? '-')" pretitle="Jadwal Kuliah">
    <x-slot:actions>
        <x-tabler.button type="back" :href="route('lab.jadwal.index')" />
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.jadwal.assignments.create', encryptId($jadwal->jadwal_kuliah_id))" modal-title="Tambah Assignment PC" />
    </x-slot:actions>
</x-tabler.page-header>
        </div>
    </div>

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-assignments"
                    route="{{ route('lab.jadwal.assignments.data', encryptId($jadwal->jadwal_kuliah_id)) }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'mahasiswa_npm', 'name' => 'user.username', 'title' => 'NPM'],
                        ['data' => 'mahasiswa_nama', 'name' => 'user.name', 'title' => 'Nama Mahasiswa'],
                        ['data' => 'nomor_pc', 'name' => 'nomor_pc', 'title' => 'Nomor PC'],
                        ['data' => 'nomor_loker', 'name' => 'nomor_loker', 'title' => 'Nomor Loker'],
                        ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'render' => 'function(data){ return data == 1 ? \'<span class="badge bg-success text-white">Aktif</span>\' : \'<span class="badge bg-secondary text-white">Non-Aktif</span>\'; }'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ]"
                />
            </div>
        </div>
@endsection
