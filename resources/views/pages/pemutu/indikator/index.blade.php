@extends('layouts.tabler.app')
@section('title', 'Data Indikator')

@section('header')
<x-tabler.page-header title="Indikator SPMI {{ $siklus['tahun'] }}" pretitle="Penetapan">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-3">
            <x-tabler.button href="{{ route('pemutu.indikator.create', ['type' => $activeType]) }}"  type="create" text="{{ $activeType === 'performa' ? 'Indikator KPI' : 'Indikator ' . ucfirst($activeType) }}" />
            <div class="nav nav-pills" id="top-tabs" role="tablist">
                <a href="#tab-akademik" class="nav-link active" data-bs-toggle="tab" role="tab">
                    <i class="ti ti-school me-2"></i>Akademik
                </a>
                <a href="#tab-non-akademik" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                    <i class="ti ti-building-community me-2"></i>Non Akademik
                </a>
            </div>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="tab-content">
    @foreach(['akademik', 'non_akademik'] as $type)
        @php 
            $periode = $siklus[$type]; 
            $typeId = str_replace('_', '-', $type);
        @endphp
        <div class="tab-pane {{ $type == 'akademik' ? 'active show' : '' }}" id="tab-{{ $typeId }}" role="tabpanel">
            @if($periode)
                <x-tabler.card>
                    <x-tabler.card-body class="border-bottom p-2 bg-transparent">
                        <ul class="nav nav-pills nav-fill gap-2 text-uppercase fw-bold" id="indikator-types-{{ $typeId }}" role="tablist">
                            @foreach($types as $key => $label)
                            <li class="nav-item">
                                <a href="{{ route('pemutu.indikator.index', ['type' => $key]) }}" 
                                   class="nav-link py-2 {{ $activeType == $key ? 'active fw-bold shadow-sm' : '' }}">
                                   Indikator {{ $label }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </x-tabler.card-body>
                    <x-tabler.card-header>
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            <x-tabler.datatable-page-length :dataTableId="'indikator-table-' . $typeId" />
                            <x-tabler.datatable-filter :dataTableId="'indikator-table-' . $typeId" type="button" :target="'#indikator-table-' . $typeId . '-filter-area'" />
                            <x-tabler.datatable-search :dataTableId="'indikator-table-' . $typeId" />
                        </div>
                    </x-tabler.card-header>
                    <div class="collapse" id="indikator-table-{{ $typeId }}-filter-area">
                        <x-tabler.datatable-filter :dataTableId="'indikator-table-' . $typeId" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dokumen_id" id="dokumen_id_{{ $typeId }}" label="Standar / Dokumen" type="select2" placeholder="" :options="$dokumens">
                                        <option value="all">Semua Standar</option>
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="renstra_poin_id" id="renstra_poin_id_{{ $typeId }}" label="Poin Renstra" type="select2" placeholder="" :options="$renstraOptions">
                                        <option value="all">Semua Poin Renstra</option>
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="label_ids[]" id="label_ids_{{ $typeId }}" label="Label Indikator" type="select2" placeholder="" :options="$labelParents" multiple="true">
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <x-tabler.card-body class="p-0">
                        <x-tabler.datatable
                            id="indikator-table-{{ $typeId }}"
                            route="{{ route('pemutu.indikator.data', ['type' => $activeType, 'periode' => $periode->periode]) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                                ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Dokumen Induk', 'searchable' => false, 'orderable' => false],
                                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                                ['data' => 'kelompok_indikator', 'name' => 'kelompok_indikator', 'title' => 'Kelompok', 'class' => 'text-center', 'orderable' => false],
                                ['data' => 'jenis_data', 'name' => 'jenis_data', 'title' => 'Jenis Data', 'class' => 'text-center', 'orderable' => false],
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
                            text="Data periode {{ str_replace('_', ' ', $type) }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                            icon="ti ti-calendar-off" 
                        />
                    </x-tabler.card-body>
                </x-tabler.card>
            @endif
        </div>
    @endforeach
</div>
@endsection
