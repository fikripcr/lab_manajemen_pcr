@extends('layouts.tabler.app')
@section('title', 'Isi Evaluasi Diri')

@section('header')
<x-tabler.page-header title="Isi Evaluasi Diri" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <div style="min-width: 250px;">
                <x-tabler.form-select name="unit_filter" id="unit-filter" :options="$userUnits->pluck('name', 'orgunit_id')" :selected="$selectedUnitId" />
            </div>
            <a href="{{ route('pemutu.evaluasi-diri.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
@if($unit)
<div class="card">
    <div class="card-header border-bottom py-3">
        <h3 class="card-title">Daftar Indikator</h3>
    </div>
    <div class="card-body border-bottom py-3">
            <x-tabler.datatable 
                id="table-ed"
                route="{{ route('pemutu.evaluasi-diri.data', $periode->encrypted_periodespmi_id) }}"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                    ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                    ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian', 'width' => '15%'],
                    ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'width' => '30%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ]"
            />
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const unitFilter = document.getElementById('unit-filter');
        if (unitFilter) {
            unitFilter.addEventListener('change', function() {
                const table = $('#table-ed').DataTable();
                const url = new URL(table.ajax.url());
                url.searchParams.set('unit_id', this.value);
                table.ajax.url(url.toString()).load();
            });
        }
    });
</script>
@endpush

@else
<div class="card">
    <div class="card-body">
        <x-tabler.empty-state 
            title="Tidak Terdaftar di Tim Mutu" 
            text="{{ session('warning') ?? 'Anda tidak terdaftar dalam Tim Mutu atau Unit manapun untuk periode ini.' }}"
            icon="ti ti-lock-access" 
        />
    </div>
</div>
@endif
@endsection
