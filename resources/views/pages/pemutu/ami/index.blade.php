@extends('layouts.tabler.app')
@section('title', 'Audit Mutu Internal - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Audit Mutu Internal (AMI) {{ $siklus['tahun'] }}" pretitle="Evaluasi">
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
            $typeId = str_replace('_', '-', $type);
            $prevYear = $periode ? (int)$periode->periode - 1 : null;
        @endphp
        <div class="tab-pane {{ $type == 'akademik' ? 'active show' : '' }}" id="tab-{{ $typeId }}" role="tabpanel">
            @if($periode)
                @php $jadwalTersedia = $periode->ami_awal && $periode->ami_akhir; @endphp
                
                <x-tabler.card>
                    <x-tabler.card-header class="border-bottom-0 pt-4">
                        <ul class="nav nav-pills card-header-pills" id="ami-tabs-{{ $typeId }}" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ami-{{ $typeId }}" class="nav-link active" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-shield-check me-2"></i>Audit Mutu Internal
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-te-{{ $typeId }}" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                                    <i class="ti ti-search me-2"></i>Tinjauan Efektivitas ({{ $prevYear }})
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-rtp-{{ $typeId }}" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                                    <i class="ti ti-pennant me-2"></i>Rencana Tindakan Perbaikan (RTP)
                                </a>
                            </li>
                        </ul>
                    </x-tabler.card-header>

                    <div class="tab-content">
                        {{-- SUB-TAB: AMI --}}
                        <div class="tab-pane active show" id="tab-ami-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Periode {{ $periode->jenis_periode }} {{ $periode->periode }}</h3>
                                        <div class="text-muted small">
                                            @if($jadwalTersedia)
                                                <i class="ti ti-calendar me-1"></i>
                                                Jadwal: {{ $periode->ami_awal->format('d M') }} - {{ $periode->ami_akhir->format('d M Y') }}
                                            @else
                                                <span class="text-warning"><i class="ti ti-alert-triangle me-1"></i> Jadwal Belum Diatur</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-ami-' . $typeId" />
                                        <x-tabler.datatable-filter :dataTableId="'table-ami-' . $typeId" type="button" :target="'#table-ami-' . $typeId . '-filter-area'" />
                                        <x-tabler.datatable-search :dataTableId="'table-ami-' . $typeId" />
                                    </div>
                                </div>
                            </x-tabler.card-body>
                            <div class="collapse" id="table-ami-{{ $typeId }}-filter-area">
                                <x-tabler.datatable-filter :dataTableId="'table-ami-' . $typeId" type="bare">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <x-tabler.form-select name="unit_id" id="unit_id_{{ $typeId }}" label="Unit / Area" placeholder="">
                                                <option value="">Semua Unit</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-3">
                                            <x-tabler.form-select name="dok_id" id="dok_id_{{ $typeId }}" label="Standar / Dokumen" placeholder="">
                                                <option value="">Semua Standar</option>
                                                @foreach($rootDoks as $dok)
                                                    <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-3">
                                            <x-tabler.form-select name="ami_hasil_akhir" id="ami_hasil_akhir_{{ $typeId }}" label="Hasil AMI" placeholder="">
                                                <option value="">Semua Hasil</option>
                                                <option value="empty">Belum Dinilai</option>
                                                <option value="0">KTS</option>
                                                <option value="1">Terpenuhi</option>
                                                <option value="2">Terlampaui</option>
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-3">
                                            <x-tabler.form-select name="ed_status" id="ed_status_{{ $typeId }}" label="Status ED" placeholder="">
                                                <option value="">Semua Status</option>
                                                <option value="filled">Sudah Isi</option>
                                                <option value="empty">Belum Isi</option>
                                            </x-tabler.form-select>
                                        </div>
                                    </div>
                                </x-tabler.datatable-filter>
                            </div>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-ami-{{ $typeId }}"
                                    route="{{ route('pemutu.ami.data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                        ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Status ED', 'width' => '10%', 'class' => 'text-center', 'orderable' => false],
                                        ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Hasil AMI', 'width' => '15%', 'class' => 'text-left', 'orderable' => false],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'AMI', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                        </div>

                        {{-- SUB-TAB: TE --}}
                        <div class="tab-pane" id="tab-te-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Tinjauan Efektivitas (Hasil AMI {{ $prevYear }})</h3>
                                        <p class="text-muted mb-0 small">Daftar temuan KTS dari periode <span class="badge bg-purple-lt text-purple small">AMI {{ $prevYear }}</span> yang harus ditinjau perbaikannya.</p>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-te-' . $typeId" />
                                        <x-tabler.datatable-filter :dataTableId="'table-te-' . $typeId" type="button" :target="'#table-te-' . $typeId . '-filter-area'" />
                                        <x-tabler.datatable-search :dataTableId="'table-te-' . $typeId" />
                                    </div>
                                </div>
                            </x-tabler.card-body>
                            <div class="collapse" id="table-te-{{ $typeId }}-filter-area">
                                <x-tabler.datatable-filter :dataTableId="'table-te-' . $typeId" type="bare">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="unit_id" id="unit_id_te_{{ $typeId }}" label="Unit / Area" placeholder="">
                                                <option value="">Semua Unit</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="dok_id" id="dok_id_te_{{ $typeId }}" label="Standar / Dokumen" placeholder="">
                                                <option value="">Semua Standar</option>
                                                @foreach($rootDoks as $dok)
                                                    <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="te_status" id="te_status_{{ $typeId }}" label="Status Tinjauan" placeholder="">
                                                <option value="">Semua Status</option>
                                                <option value="filled">Sudah Ditinjau</option>
                                                <option value="empty">Belum Ditinjau</option>
                                            </x-tabler.form-select>
                                        </div>
                                    </div>
                                </x-tabler.datatable-filter>
                            </div>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-te-{{ $typeId }}"
                                    route="{{ route('pemutu.ami.te-data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                        ['data' => 'rtp', 'name' => 'rtp', 'title' => 'Rencana (RTP)', 'width' => '15%'],
                                        ['data' => 'ptp', 'name' => 'ptp', 'title' => 'Pelaksanaan (PTP)', 'width' => '15%'],
                                        ['data' => 'te', 'name' => 'te', 'title' => 'Tinjauan (TE)', 'width' => '15%'],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                        </div>

                        {{-- SUB-TAB: RTP ONLY (Findings) --}}
                        <div class="tab-pane" id="tab-rtp-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Rencana Tindakan Perbaikan (RTP)</h3>
                                        <p class="text-muted mb-0 small">Indikator dengan temuan Audit (KTS) yang memerlukan rencana perbaikan.</p>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-rtp-only-' . $typeId" />
                                        <x-tabler.datatable-filter :dataTableId="'table-rtp-only-' . $typeId" type="button" :target="'#table-rtp-only-' . $typeId . '-filter-area'" />
                                        <x-tabler.datatable-search :dataTableId="'table-rtp-only-' . $typeId" />
                                    </div>
                                </div>
                            </x-tabler.card-body>
                            <div class="collapse" id="table-rtp-only-{{ $typeId }}-filter-area">
                                <x-tabler.datatable-filter :dataTableId="'table-rtp-only-' . $typeId" type="bare">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="unit_id" id="unit_id_rtp_{{ $typeId }}" label="Unit / Area" placeholder="">
                                                <option value="">Semua Unit</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="dok_id" id="dok_id_rtp_{{ $typeId }}" label="Standar / Dokumen" placeholder="">
                                                <option value="">Semua Standar</option>
                                                @foreach($rootDoks as $dok)
                                                    <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-4">
                                            <x-tabler.form-select name="rtp_status" id="rtp_status_{{ $typeId }}" label="Status RTP" placeholder="">
                                                <option value="">Semua Status</option>
                                                <option value="filled">Sudah Mengisi</option>
                                                <option value="empty">Belum Mengisi</option>
                                            </x-tabler.form-select>
                                        </div>
                                    </div>
                                </x-tabler.datatable-filter>
                            </div>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-rtp-only-{{ $typeId }}"
                                    route="{{ route('pemutu.ami.data', [$periode->encrypted_periodespmi_id, 'ami_hasil_akhir' => 0]) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                        ['data' => 'auditor_recom', 'name' => 'auditor_recom', 'title' => 'Rekomendasi Auditor', 'width' => '15%'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                        ['data' => 'rtp_isi', 'name' => 'rtp_isi', 'title' => 'Rencana Perbaikan', 'width' => '20%', 'class' => 'text-left', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'rtp_tgl', 'name' => 'rtp_tgl', 'title' => 'Tgl Pelaksanaan', 'width' => '10%', 'class' => 'text-center'],
                                        ['data' => 'action_rtp', 'name' => 'action_rtp', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                    </div> {{-- end sub-tab content --}}
                    </div> {{-- end tab-content --}}
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

