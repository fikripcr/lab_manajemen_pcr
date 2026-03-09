@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Manajemen Data Pegawai">
    <x-slot:actions>
        <x-tabler.button href="{{ route('hr.pegawai.upload-photo') }}" class="btn-outline-primary d-none d-sm-inline-block me-2" icon="ti ti-upload" text="Upload Foto Pegawai" />
        <x-tabler.button type="create" href="{{ route('hr.pegawai.create') }}" class="d-none d-sm-inline-block" text="Tambah Pegawai" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length dataTableId="pegawai-table" />
            </div>
            <div>
                <x-tabler.datatable-search dataTableId="pegawai-table" />
            </div>
            <div>
                <x-tabler.datatable-filter dataTableId="pegawai-table">
                    <div style="min-width: 150px;">
                        <x-tabler.form-select name="status" placeholder="Semua Status" class="mb-0"
                            :options="['Aktif' => 'Aktif', 'Non-Aktif' => 'Non-Aktif']" />
                    </div>
                </x-tabler.datatable-filter>
            </div>
        </div>
    </x-tabler.card-header>

    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="pegawai-table"
            route="{{ route('hr.pegawai.index') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'nama_lengkap', 'name' => 'nama', 'title' => 'Nama Lengkap'],
                ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
                ['data' => 'unit', 'name' => 'unit', 'title' => 'Unit (Dept/Prodi)'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
