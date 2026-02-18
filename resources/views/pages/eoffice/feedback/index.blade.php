@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office"/>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Feedback</h3>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <x-tabler.form-select name="f_jenislayanan" label="Jenis Layanan">
                    <option value="">Semua Jenis Layanan</option>
                    @foreach($jenisLayananList as $jl)
                        <option value="{{ encryptId($jl->jenislayanan_id) }}">{{ $jl->nama_layanan }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-3">
                <x-tabler.form-input type="date" name="f_tgl_start" label="Tanggal Mulai" />
            </div>
            <div class="col-md-3">
                <x-tabler.form-input type="date" name="f_tgl_end" label="Tanggal Akhir" />
            </div>
            <div class="col-md-3">
                <x-tabler.button type="button" class="btn-primary w-100" id="btn-filter" icon="ti ti-filter" text="Filter" />
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="tbl-feedback"
        route="{{ route('eoffice.feedback.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'title' => '#', 'width' => '5%'],
            ['data' => 'no_layanan', 'title' => 'No Layanan'],
            ['data' => 'nama_layanan', 'title' => 'Jenis Layanan'],
            ['data' => 'rating_stars', 'title' => 'Rating'],
            ['data' => 'feedback', 'title' => 'Feedback'],
            ['data' => 'tanggal', 'title' => 'Tanggal']
        ]"
    />
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        // x-tabler.datatable initializes the table automatically.
        // We need to access the instance to apply custom filtering.
        
        // Wait for the table to be initialized (checking window['DT_tbl-feedback'])
        const checkTableInterval = setInterval(function() {
            if (window['DT_tbl-feedback']) {
                clearInterval(checkTableInterval);
                const table = window['DT_tbl-feedback'];
                
                // Override ajax data function to include filters
                const oldAjax = table.ajax.params; // logic to extend ajax params is slightly different in DataTables
                
                // We need to hooking into the ajax request to add our parameters
                // The standard way with DataTables is updating the 'data' function in ajax config, 
                // but the component initializes it. 
                // However, we can use 'preXhr' event or just reload with params if we want simple filtering.
                // Or better, we can re-assign the ajax.data function.
                
                table.on('preXhr.dt', function ( e, settings, data ) {
                    data.jenislayanan_id = document.getElementById('f_jenislayanan').value;
                    data.f_tgl_start     = document.getElementById('f_tgl_start').value;
                    data.f_tgl_end       = document.getElementById('f_tgl_end').value;
                });

                document.getElementById('btn-filter').addEventListener('click', function() {
                    table.ajax.reload();
                });
            }
        }, 100);
    }
});
</script>
@endpush
