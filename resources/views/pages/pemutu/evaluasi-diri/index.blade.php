@extends('layouts.tabler.app')
@section('title', 'Evaluasi Diri - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Evaluasi Diri SPMI {{ $siklus['tahun'] }}" pretitle="Evaluasi">
    <x-slot:actions>
        <div class="btn-group p-1 bg-light rounded-pill shadow-sm" style="border: 1px solid #e6e8e9;">
            <a href="{{ route('pemutu.set-kelompok', 'akademik') }}" 
               class="btn {{ $activeKelompok === 'akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-school me-2"></i>Akademik
            </a>
            <a href="{{ route('pemutu.set-kelompok', 'non_akademik') }}" 
               class="btn {{ $activeKelompok === 'non_akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-building-community me-2"></i>Non Akademik
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @if($periode)
        @php $jadwalTersedia = $periode->ed_awal && $periode->ed_akhir; @endphp
        
        <x-tabler.card>
            <x-tabler.card-header class="border-bottom-0 pt-4">
                <ul class="nav nav-pills card-header-pills" id="ed-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-ed" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-checklist me-2"></i>Evaluasi Diri
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-ptp" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                            <i class="ti ti-history me-2"></i>Pelaksanaan Tindakan Perbaikan
                        </a>
                    </li>
                </ul>
            </x-tabler.card-header>

            <div class="tab-content">
                {{-- SUB-TAB: EVALUASI DIRI --}}
                <div class="tab-pane active show" id="tab-ed" role="tabpanel">
                    <x-tabler.card-body class="border-top">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-1">Monitoring Pengisian - {{ ucfirst(str_replace('_', ' ', $activeKelompok)) }}</h3>
                                <div class="text-muted small mt-1">
                                    @php $periodeInfo = pemutuPeriodeStatus($periode->ed_awal, $periode->ed_akhir); @endphp
                                    @if($periode->ed_awal && $periode->ed_akhir)
                                        <i class="ti ti-calendar me-1"></i>
                                        Jadwal: {{ $periode->ed_awal->format('d M Y') }} s.d. {{ $periode->ed_akhir->format('d M Y') }}
                                    @endif
                                    <span class="badge bg-{{ $periodeInfo['color'] }}-lt ms-2">{{ $periodeInfo['status_text'] }}</span>
                                </div>
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <x-tabler.datatable-page-length dataTableId="table-ed" />
                                <x-tabler.datatable-search dataTableId="table-ed" />
                                <x-tabler.datatable-filter dataTableId="table-ed" type="button" target="#table-ed-filter-area" />
                            </div>
                        </div>
                    </x-tabler.card-body>
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

                {{-- SUB-TAB: PTP --}}
                <div class="tab-pane" id="tab-ptp" role="tabpanel">
                    <x-tabler.card-body class="border-top">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-1">Pelaksanaan Tindakan Perbaikan</h3>
                                <p class="text-muted mb-0 small">KTS dari periode tahun lalu yang harus dilaporkan perbaikannya.</p>
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <x-tabler.datatable-page-length dataTableId="table-ptp" />
                                <x-tabler.datatable-search dataTableId="table-ptp" />
                                <x-tabler.datatable-filter dataTableId="table-ptp" type="button" target="#table-ptp-filter-area" />
                            </div>
                        </div>
                    </x-tabler.card-body>
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
            </div>
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

