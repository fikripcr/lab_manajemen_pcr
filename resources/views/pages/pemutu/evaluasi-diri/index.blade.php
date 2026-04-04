@extends('layouts.tabler.app')
@section('title', 'Evaluasi Diri - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Evaluasi Diri SPMI {{ $siklus['tahun'] }}" pretitle="Evaluasi">
    <x-slot:actions>
        <x-pemutu.kelompok-selector :active-kelompok="$activeKelompok" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @if($periode)
        <x-pemutu.active-period :periode="$periode" type="ed" />

            <x-tabler.card>
            <x-tabler.card-header class="border-bottom px-4 pt-3 pb-3 d-flex justify-content-between align-items-center">
                <ul class="nav nav-pills card-header-pills" id="ed-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('pemutu.evaluasi-diri.index', ['tab' => 'ed']) }}" class="nav-link {{ $activeTab === 'ed' ? 'active' : '' }}">
                            <i class="ti ti-checklist me-2"></i>Evaluasi Diri
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('pemutu.evaluasi-diri.index', ['tab' => 'ptp']) }}" class="nav-link {{ $activeTab === 'ptp' ? 'active' : '' }}">
                            <i class="ti ti-history me-2"></i>Pelaksanaan Tindakan Perbaikan
                            <span class="text-muted ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="KTS dari periode tahun lalu yang harus dilaporkan perbaikannya."><i class="ti ti-info-circle"></i></span>
                        </a>
                    </li>
                </ul>

                <div class="card-actions mb-0">
                    @if($activeTab === 'ed')
                    <div class="d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-ed" />
                        <x-tabler.datatable-search dataTableId="table-ed" />
                        <x-tabler.datatable-filter dataTableId="table-ed" type="button" target="#table-ed-filter-area" />
                    </div>
                    @elseif($activeTab === 'ptp')
                    <div class="d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-ptp" />
                        <x-tabler.datatable-search dataTableId="table-ptp" />
                        <x-tabler.datatable-filter dataTableId="table-ptp" type="button" target="#table-ptp-filter-area" />
                    </div>
                    @endif
                </div>
            </x-tabler.card-header>

            <div>
                {{-- SUB-TAB: EVALUASI DIRI --}}
                @if($activeTab === 'ed')
                <div>
                    <div class="collapse" id="table-ed-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-ed" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="unit_id" id="unit_id" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dok_id" id="dok_id" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->kode ?? '' }} - {{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="ed_status" id="ed_status" label="Status Pengisian ED" placeholder="">
                                        <option value="all">Semua</option>
                                        <option value="filled">Sudah Isi</option>
                                        <option value="empty">Belum Isi</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <div class="table-responsive border-top">
                        <x-tabler.datatable
                            id="table-ed"
                            route="{{ route('pemutu.evaluasi-diri.data', $periode->encrypted_periodespmi_id) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator'],
                                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian & Skala', 'width' => '15%', 'class' => 'text-center'],
                                ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis Capaian', 'width' => '30%'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ]"
                        />
                    </div>
                </div>
                @endif

                {{-- SUB-TAB: PTP --}}
                @if($activeTab === 'ptp')
                <div>
                    <div class="collapse" id="table-ptp-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-ptp" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="unit_id" id="unit_id_ptp" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dok_id" id="dok_id_ptp" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="ptp_status" id="ptp_status" label="Status Perbaikan" placeholder="">
                                        <option value="all">Semua Status</option>
                                        <option value="filled">Sudah Isi</option>
                                        <option value="empty">Belum Isi</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <div class="table-responsive border-top">
                        <x-tabler.datatable
                            id="table-ptp"
                            route="{{ route('pemutu.evaluasi-diri.ptp-data', $periode->encrypted_periodespmi_id) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator'],
                                ['data' => 'rtp_isi', 'name' => 'rtp_isi', 'title' => 'RTP (Tahun Lalu)', 'width' => '25%'],
                                ['data' => 'ptp_isi', 'name' => 'ptp_isi', 'title' => 'Pelaksanaan (PTP)', 'width' => '25%'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ]"
                        />
                    </div>
                </div>
                @endif
            </div>
            </x-tabler.card>
        </div>
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

