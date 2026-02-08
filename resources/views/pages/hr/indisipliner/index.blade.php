@extends('layouts.admin.app')

@section('title', 'Data Indisipliner')

@section('header')
<x-tabler.page-header title="Data Indisipliner" pretitle="Manajemen HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Data" class="ajax-modal-btn" data-url="{{ route('hr.indisipliner.create') }}" data-modal-title="Tambah Data Indisipliner" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'indisipliner-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'indisipliner-table'" />
            </div>
            <div class="ms-auto">
                <select class="form-select form-select-sm" id="filter-tahun" style="min-width: 100px;">
                    <option value="">Semua Tahun</option>
                    @for ($i = date('Y'); $i >= 2019; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.flash-message />
        <x-tabler.datatable 
            id="indisipliner-table"
            route="{{ route('hr.indisipliner.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                ['data' => 'tgl_indisipliner', 'name' => 'tgl_indisipliner', 'title' => 'Tanggal', 'className' => 'text-center'],
                ['data' => 'pegawai', 'name' => 'pegawai', 'title' => 'Pegawai', 'orderable' => false],
                ['data' => 'jenis', 'name' => 'jenis', 'title' => 'Jenis Pelanggaran'],
                ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '100px'],
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter by year
    const filterTahun = document.getElementById('filter-tahun');
    if (filterTahun) {
        filterTahun.addEventListener('change', function() {
            const table = window.LaravelDataTables['indisipliner-table'];
            if (table) {
                table.ajax.url('{{ route('hr.indisipliner.data') }}?f_tahun=' + this.value).load();
            }
        });
    }
});
</script>
@endpush
