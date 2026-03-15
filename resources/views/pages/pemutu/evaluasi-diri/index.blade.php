@extends('layouts.tabler.app')
@section('title', 'Evaluasi Diri - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Evaluasi Diri SPMI {{ $siklus['tahun'] }}" pretitle="Evaluasi">
    <x-slot:actions>
        <div class="nav nav-pills" id="top-tabs" role="tablist">
            <a href="#tab-akademik" class="nav-link active" data-bs-toggle="tab" role="tab">
                <i class="ti ti-school me-2"></i>Akademik
            </a>
            <a href="#tab-non-akademik" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                <i class="ti ti-building-community me-2"></i>Non Akademik
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="tab-content">
    @foreach(['akademik', 'non_akademik'] as $type)
        @php 
            $periode = $siklus[$type]; 
            $userUnits = ${$type . 'Units'};
            $typeId = str_replace('_', '-', $type);
        @endphp
        <div class="tab-pane {{ $type == 'akademik' ? 'active show' : '' }}" id="tab-{{ $typeId }}" role="tabpanel">
            @if($periode)
                @php $jadwalTersedia = $periode->ed_awal && $periode->ed_akhir; @endphp
                
                <x-tabler.card>
                    <x-tabler.card-header class="border-bottom-0 pt-4">
                        <ul class="nav nav-pills card-header-pills" id="ed-tabs-{{ $typeId }}" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ed-{{ $typeId }}" class="nav-link active" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-checklist me-2"></i>Evaluasi Diri
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ptp-{{ $typeId }}" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                                    <i class="ti ti-history me-2"></i>Pelaksanaan Perbaikan
                                </a>
                            </li>
                        </ul>
                    </x-tabler.card-header>

                    <div class="tab-content">
                        {{-- SUB-TAB: EVALUASI DIRI --}}
                        <div class="tab-pane active show" id="tab-ed-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Periode {{ $periode->jenis_periode }} {{ $periode->periode }}</h3>
                                        <div class="text-muted small">
                                            @if($jadwalTersedia)
                                                <i class="ti ti-calendar me-1"></i>
                                                Jadwal: {{ $periode->ed_awal->format('d M') }} - {{ $periode->ed_akhir->format('d M Y') }}
                                            @else
                                                <span class="text-warning"><i class="ti ti-alert-triangle me-1"></i> Jadwal Belum Diatur</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-ed-' . $typeId" />
                                        <x-tabler.datatable-filter :dataTableId="'table-ed-' . $typeId">
                                            <div>
                                               <x-tabler.form-select name="unit_id" id="unit_id_{{ $typeId }}" class="unit-filter" placeholder="Filter Area / Unit" :options="$userUnits->pluck('name', 'encrypted_org_unit_id')" type="select2" />
                                            </div>
                                        </x-tabler.datatable-filter>
                                        <x-tabler.datatable-search :dataTableId="'table-ed-' . $typeId" />
                                    </div>
                                </x-tabler.card-body>
                            </div>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-ed-{{ $typeId }}"
                                    route="{{ route('pemutu.evaluasi-diri.data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '15%','class' => 'text-left' ],
                                        ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian', 'width' => '15%','class' => 'text-center'  ],
                                        ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'width' => '25%'],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                        </div>

                        {{-- SUB-TAB: PTP --}}
                        <div class="tab-pane" id="tab-ptp-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Pelaksanaan Tindakan Perbaikan</h3>
                                        <p class="text-muted mb-0 small">KTS dari periode tahun lalu yang harus dilaporkan perbaikannya.</p>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-ptp-' . $typeId" />
                                        <x-tabler.datatable-search :dataTableId="'table-ptp-' . $typeId" />
                                    </div>
                                </div>
                            </x-tabler.card-body>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-ptp-{{ $typeId }}"
                                    route="{{ route('pemutu.evaluasi-diri.ptp-data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                                        ['data' => 'rtp_isi', 'name' => 'rtp_isi', 'title' => 'RTP (Tahun Lalu)', 'width' => '25%'],
                                        ['data' => 'ptp_isi', 'name' => 'ptp_isi', 'title' => 'Pelaksanaan (PTP)', 'width' => '25%'],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </x-tabler.card>
            @else
                <x-tabler.card>
                    <x-tabler.card-body class="py-5">
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

