@extends('layouts.tabler.app')
@section('title', 'Indikator Penetapan - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Indikator SPMI {{ $siklus['tahun'] }}" pretitle="Penetapan">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-3">
            <x-pemutu.kelompok-selector :active-kelompok="$activeKelompok" />

            <x-tabler.button href="{{ route('pemutu.indikator.create', ['type' => $activeType]) }}"  type="create" text="{{ $activeType === 'performa' ? 'Indikator KPI' : 'Indikator ' . ucfirst($activeType) }}" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @if($periode)
        <x-pemutu.active-period :periode="$periode" type="penetapan" />
        <x-tabler.card>
            <x-tabler.card-header class="border-bottom px-4 pt-3 pb-3 d-flex justify-content-between align-items-center">
                <ul class="nav nav-pills nav-fill gap-2 text-uppercase fw-bold m-0" style="min-width: 400px;" id="indikator-types" role="tablist">
                    @foreach($types as $key => $label)
                    <li class="nav-item">
                        <a href="{{ route('pemutu.indikator.index', ['type' => $key]) }}" 
                           class="nav-link py-2 {{ $activeType == $key ? 'active fw-bold shadow-sm' : '' }}">
                           Indikator {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="card-actions mb-0">
                    <div class="d-flex gap-2 align-items-center">
                        <x-tabler.datatable-page-length dataTableId="indikator-table" />
                        <x-tabler.datatable-search dataTableId="indikator-table" />
                        <x-tabler.datatable-filter dataTableId="indikator-table" type="button" target="#indikator-table-filter-area" />
                    </div>
                </div>
            </x-tabler.card-header>
            <div class="collapse" id="indikator-table-filter-area">
                <x-tabler.datatable-filter dataTableId="indikator-table" type="bare">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-tabler.form-select name="dokumen_id" id="dokumen_id" label="Standar / Dokumen" type="select2" placeholder="" :options="$dokumens">
                                <option value="all">Semua Standar</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-4">
                            <x-tabler.form-select name="renstra_poin_id" id="renstra_poin_id" label="Poin Renstra" type="select2" placeholder="" :options="$renstraOptions">
                                <option value="all">Semua Poin Renstra</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-4">
                            <x-tabler.form-select name="label_ids" id="label_ids" label="Label Indikator" type="select2" placeholder="Semua Label" :options="$labelParents" :multiple="true"/>
                        </div>
                    </div>
                </x-tabler.datatable-filter>
            </div>
            <x-tabler.card-body class="p-0">
                <x-tabler.datatable
                    id="indikator-table"
                    route="{{ route('pemutu.indikator.data', ['type' => $activeType, 'periode' => $periode->periode, 'kelompok_indikator' => str_replace('_', ' ', ucwords($activeKelompok, '_'))]) }}"
                    :columns="[
                        ['data' => 'no', 'name' => 'no', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Dokumen Induk', 'searchable' => false, 'orderable' => false],
                        ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                        ['data' => 'renstra_poin', 'name' => 'renstra_poin', 'title' => 'Poin Renstra', 'searchable' => false, 'orderable' => false],
                        ['data' => 'labels', 'name' => 'labels', 'title' => 'Labels', 'orderable' => false, 'searchable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
                    ]"
                />
            </x-tabler.card-body>
        </x-tabler.card>
    @else
        <x-tabler.card>
            <x-tabler.card-body class="py-5 text-center">
                <x-tabler.empty-state 
                    title="Periode Belum Tersedia" 
                    text="Data periode {{ str_replace('_', ' ', $activeKelompok) }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                    icon="ti ti-calendar-off" 
                />
            </x-tabler.card-body>
        </x-tabler.card>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-initialize Select2 when filter area is shown to fix width issues
    const filterAreas = document.querySelectorAll('.collapse[id$="-filter-area"]');
    filterAreas.forEach(area => {
        area.addEventListener('shown.bs.collapse', function () {
            if (window.initOfflineSelect2) {
                window.initOfflineSelect2();
            }
        });
    });
});
</script>
@endpush
