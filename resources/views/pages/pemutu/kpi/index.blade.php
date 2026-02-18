@extends('layouts.admin.app')
@section('title', 'Sasaran Kinerja (KPI)')

@section('header')
<x-tabler.page-header title="Sasaran Kinerja (KPI)" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.kpi.create') }}" icon="ti ti-plus" text="Tambah Sasaran Kinerja" class="btn-primary" />
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
    
    <div id="filter-section" class="collapse">
        <form id="kpi-table-filter">
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
                         <x-tabler.button type="button" id="btn-reset-filter" class="btn-ghost-danger" text="Reset Filter" />
                         <x-tabler.button type="button" id="btn-apply-filter" class="btn-primary" text="Terapkan Filter" />
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <x-tabler.datatable 
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
        // Use a small delay to ensure CustomDataTables is initialized
        setTimeout(() => {
            const tableInstance = window['DT_kpi-table'];
            const table = tableInstance ? tableInstance.table : null;

            if (!table) {
                console.error('DataTable instance not found for kpi-table');
                return;
            }

            $('#btn-apply-filter').on('click', function() {
                table.draw();
            });

            $('#btn-reset-filter').on('click', function() {
                const filterForm = document.getElementById('kpi-table-filter');
                if (filterForm) {
                    filterForm.reset();
                    // Manually trigger change for Select2
                    $(filterForm).find('.select2-filter').val('').trigger('change');
                }
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
        }, 100);
    });
</script>
@endpush
