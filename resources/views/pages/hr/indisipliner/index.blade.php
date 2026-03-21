@extends('layouts.tabler.app')

@section('title', 'Data Indisipliner')

@section('header')
<x-tabler.page-header title="Data Indisipliner" pretitle="Manajemen HR">
    <x-slot:actions>
        <div class="d-flex gap-2">
            <x-tabler.datatable-filter dataTableId="indisipliner-table" :useCollapse="true">
                <div class="col-12">
                    <x-tabler.form-select name="f_tahun" label="Filter Tahun" class="mb-0">
                        <option value="">Semua Tahun</option>
                        @for ($i = date('Y'); $i >= 2019; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </x-tabler.form-select>
                </div>
            </x-tabler.datatable-filter>
            <x-tabler.button type="create" class="ajax-modal-btn" data-url="{{ route('hr.indisipliner.create') }}" modal-title="Tambah Data Indisipliner" text="Tambah Data" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card class="overflow-hidden">
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'indisipliner-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'indisipliner-table'" />
            </div>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="indisipliner-table"
            route="{{ route('hr.indisipliner.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '50px'],
                ['data' => 'tgl_indisipliner', 'name' => 'tgl_indisipliner', 'title' => 'Tanggal', 'class' => 'text-center'],
                ['data' => 'hr_pegawai', 'name' => 'hr_pegawai', 'title' => 'Pegawai', 'orderable' => false],
                ['data' => 'jenis', 'name' => 'jenis', 'title' => 'Jenis Pelanggaran'],
                ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '100px'],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // x-tabler.datatable and x-tabler.datatable-filter handle everything automatically
});
</script>
@endpush
