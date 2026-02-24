@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office"/>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Feedback</h3>
        <div class="card-actions">
            <x-tabler.datatable-filter :dataTableId="'tbl-feedback'" :useCollapse="true">
                <div class="row g-2">
                    <div class="col-12">
                        <x-tabler.form-select name="jenislayanan_id" label="Jenis Layanan" id="f_jenislayanan">
                            <option value="all">Semua Jenis Layanan</option>
                            @foreach($jenisLayananList as $jl)
                                <option value="{{ encryptId($jl->jenislayanan_id) }}">{{ $jl->nama_layanan }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    <div class="col-12">
                        <x-tabler.form-input type="date" name="f_tgl_start" label="Tanggal Mulai" id="f_tgl_start" />
                    </div>
                    <div class="col-12">
                        <x-tabler.form-input type="date" name="f_tgl_end" label="Tanggal Akhir" id="f_tgl_end" />
                    </div>
                </div>
            </x-tabler.datatable-filter>
        </div>
    </div>
    <x-tabler.datatable
        id="tbl-feedback"
        route="{{ route('eoffice.feedback.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'title' => '#', 'class' => 'text-center', 'width' => '5%'],
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
    // Manual filtering JS removed. Standardized component handles it.
</script>
@endpush
