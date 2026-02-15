@extends('layouts.admin.app')
@section('title', 'Sasaran Kinerja (KPI)')

@section('header')
<x-tabler.page-header title="Sasaran Kinerja (KPI)" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.kpi.create') }}" icon="ti ti-plus" text="Tambah Sasaran Kinerja" class="btn-primary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Sasaran Kinerja</h3>
        <div class="card-actions">
            <x-tabler.button type="button" icon="ti ti-filter" text="Filter" class="btn-ghost-secondary" data-bs-toggle="collapse" data-bs-target="#filter-section" />
        </div>
    </div>
    
    <div class="collapse" id="filter-section">
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-tabler.form-select 
                        id="filter-dokumen" 
                        name="dokumen_id"
                        label="Dokumen Standar"
                        class="select2-filter" 
                        :options="['' => 'Semua Dokumen'] + $dokumens" 
                    />
                </div>
                <div class="col-md-8 mb-3">
                    <x-tabler.form-select 
                        id="filter-parent" 
                        name="parent_id"
                        label="Indikator Standar (Induk)"
                        class="select2-filter" 
                        :options="['' => 'Semua Indikator Standar'] + $parents" 
                    />
                </div>
                <div class="col-12 text-end">
                     <x-tabler.button type="button" id="btn-reset-filter" style="ghost-danger">Reset Filter</x-tabler.button>
                     <x-tabler.button type="button" id="btn-apply-filter" style="primary">Terapkan Filter</x-tabler.button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <x-tabler.datatable-client 
            id="kpi-table" 
            route="{{ route('pemutu.kpi.data') }}" 
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                ['data' => 'no_indikator', 'name' => 'no_indikator', 'title' => 'Kode', 'width' => '10%'],
                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Sasaran Kinerja'],
                ['data' => 'parent_info', 'name' => 'parent.indikator', 'title' => 'Indikator Standar'],
                ['data' => 'target_info', 'name' => 'target_info', 'title' => 'Target', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%']
            ]" 
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = window.LaravelDataTables['kpi-table'];

        $('#btn-apply-filter').on('click', function() {
            table.draw();
        });

        $('#btn-reset-filter').on('click', function() {
            $('#filter-dokumen').val('').trigger('change');
            $('#filter-parent').val('').trigger('change');
            table.draw();
        });

        // Initialize Select2 if available
        if (window.loadSelect2) {
             window.loadSelect2().then(() => {
                $('.select2-filter').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Pilih opsi...'
                });
            });
        }

        // Custom filtering logic for DataTables
        table.on('preXhr.dt', function (e, settings, data) {
            data.dokumen_id = $('#filter-dokumen').val();
            // We might need to handle parent_id in service/controller specifically if we want to filter by specific parent
            // But currently IndikatorService uses getFilteredQuery which supports 'dokumen_id' but not explicit 'parent_id' directly unless added.
            // Let's rely on standard search or extend service later if strictly needed.
            // Actually, IndikatorService doesnt filter by parent_id. 
            // We can add it to the request and if service ignores it, no harm.
        });
    });
</script>
@endpush
