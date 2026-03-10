@extends('layouts.tabler.app')
@section('title', 'Isi Evaluasi Diri')

@section('header')
<x-tabler.page-header title="Isi Evaluasi Diri" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <x-tabler.button type="back" :href="route('pemutu.evaluasi-diri.index')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
@if(!($jadwalTersedia ?? false))
<x-tabler.card>
    <x-tabler.card-body class="text-center py-5">
        <div class="mb-3">
            <span class="avatar avatar-xl rounded bg-yellow-lt">
                <i class="ti ti-calendar-off fs-1"></i>
            </span>
        </div>
        <h3>Jadwal Evaluasi Diri Belum Diatur</h3>
        <p class="text-muted">Jadwal pelaksanaan Evaluasi Diri untuk periode <strong>{{ $periode->periode }}</strong> belum ditetapkan oleh administrator. Silakan hubungi Tim Mutu untuk mengatur jadwal.</p>
        <x-tabler.button type="back" :href="route('pemutu.evaluasi-diri.index')" />
    </x-tabler.card-body>
</x-tabler.card>
@elseif($unit)
<x-tabler.card>
    <x-tabler.card-header>
        <ul class="nav nav-tabs card-header-tabs flex-grow-1" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tab-ed" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <i class="ti ti-checklist me-2"></i>Evaluasi Diri
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tab-ptp" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ti ti-history me-2"></i>Pelaksanaan Perbaikan
                </a>
            </li>
        </ul>
    </x-tabler.card-header>
    
    <div class="tab-content">
        {{-- TAB 1: EVALUASI DIRI --}}
        <div class="tab-pane active show" id="tab-ed" role="tabpanel">
            <x-tabler.card-body>
                <div class="row align-items-center">
                    <div class="col">
                        <p class="text-muted mb-0 small">Berikut adalah daftar indikator yang harus diisi <strong>Evaluasi Diri</strong> pada periode ini.</p>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <x-tabler.datatable-page-length :dataTableId="'table-ed'" />
                        <x-tabler.datatable-filter :dataTableId="'table-ed'">
                            <div>
                               <x-tabler.form-select name="unit_id" id="unit-filter" placeholder="Filter Area / Unit" :options="$userUnits->pluck('name', 'encrypted_org_unit_id')" :selected="$selectedUnitId" type="select2" />
                            </div>
                        </x-tabler.datatable-filter>
                        <x-tabler.datatable-search :dataTableId="'table-ed'" />
                    </div>
                </div>
            </x-tabler.card-body>
            <x-tabler.card-body class="p-0 table-responsive">
                <x-tabler.datatable
                    id="table-ed"
                    route="{{ route('pemutu.evaluasi-diri.data', $periode->encrypted_periodespmi_id) }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%','class' => 'text-left' ],
                        ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian', 'width' => '15%','class' => 'text-center'  ],
                        ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'width' => '30%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </x-tabler.card-body>
        </div>

        {{-- TAB 2: PELAKSANAAN PERBAIKAN (KTS TAHUN LALU) --}}
        <div class="tab-pane" id="tab-ptp" role="tabpanel">
            <x-tabler.card-body>
                <div class="row align-items-center">
                    <div class="col">
                        <p class="text-muted mb-0 small">Daftar indikator <strong>KTS (Ketidaksesuaian)</strong> dari periode tahun lalu yang pelaksanaannya harus dilaporkan.</p>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <x-tabler.datatable-page-length :dataTableId="'table-ptp'" />
                        <x-tabler.datatable-search :dataTableId="'table-ptp'" />
                    </div>
                </div>
            </x-tabler.card-body>
            <x-tabler.card-body class="p-0 table-responsive">
                <x-tabler.datatable
                    id="table-ptp"
                    route="{{ route('pemutu.evaluasi-diri.ptp-data', $periode->encrypted_periodespmi_id) }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Pernyataan Standar'],
                        ['data' => 'rtp_isi', 'name' => 'rtp_isi', 'title' => 'RTP (Tahun Lalu)', 'width' => '25%'],
                        ['data' => 'ptp_isi', 'name' => 'ptp_isi', 'title' => 'Pelaksanaan (PTP)', 'width' => '25%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </x-tabler.card-body>
        </div>
    </div>
</x-tabler.card>



@else
<x-tabler.card>
    <x-tabler.card-body>
        <x-tabler.empty-state
            title="Tidak Terdaftar di Tim Mutu"
            text="{{ session('warning') ?? 'Anda tidak terdaftar dalam Tim Mutu atau Unit manapun untuk periode ini.' }}"
            icon="ti ti-lock-access"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endif
@endsection
