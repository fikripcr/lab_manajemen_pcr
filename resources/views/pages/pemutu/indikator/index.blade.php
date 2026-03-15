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
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <x-tabler.datatable-page-length :dataTableId="'indikator-table-' . $typeId" />
                            <div class="ms-auto d-flex gap-2">
                                <x-tabler.datatable-filter :dataTableId="'indikator-table-' . $typeId">
                                    <div class="mb-2">
                                        <x-tabler.form-select name="dokumen_id" id="dokumen_id_{{ $typeId }}" placeholder="Filter Dokumen" :options="$dokumens" type="select2" />
                                    </div>
                                    <div>
                                        <x-tabler.form-select name="label_id" id="label_id_{{ $typeId }}" placeholder="Filter Label" :options="$labelParents" type="select2" />
                                    </div>
                                </x-tabler.datatable-filter>
                                <x-tabler.datatable-search :dataTableId="'indikator-table-' . $typeId" />
                            </div>
                        </div>
                    </x-tabler.card-header>
                    <x-tabler.card-body class="p-0">
                        <x-tabler.datatable
                            id="indikator-table-{{ $typeId }}"
                            route="{{ route('pemutu.indikator.data', ['type' => $activeType, 'periode' => $periode->periode]) }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                                ['data' => 'no_indikator', 'name' => 'no_indikator', 'title' => 'No. Indikator', 'width' => '10%'],
                                ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                                ['data' => 'target', 'name' => 'target', 'title' => 'Target','class' => 'text-center', 'width' => '10%'],
                                ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Dokumen Induk', 'searchable' => false, 'orderable' => false],
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
