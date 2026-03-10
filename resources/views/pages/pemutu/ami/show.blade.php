@extends('layouts.tabler.app')
@section('title', 'AMI — ' . $periode->periode)

@section('header')
<x-tabler.page-header title="Daftar Indikator AMI" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <x-tabler.button type="back" :href="route('pemutu.ami.index')" size="sm" />
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
        <h3>Jadwal AMI Belum Diatur</h3>
        <p class="text-muted">Jadwal pelaksanaan Audit Mutu Internal untuk periode <strong>{{ $periode->periode }}</strong> belum ditetapkan oleh administrator.</p>
        <x-tabler.button type="back" :href="route('pemutu.ami.index')" />
    </x-tabler.card-body>
</x-tabler.card>
@else
<x-tabler.card>
    <x-tabler.card-header >
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tab-ami" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <i class="ti ti-shield-check me-2"></i>Audit Mutu Internal
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tab-te" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ti ti-search me-2"></i>Tinjauan Efektivitas (Tahun Lalu)
                </a>
            </li>
        </ul>
    </x-tabler.card-header>
    
    <div class="tab-content">
        {{-- TAB 1: AMI --}}
        <div class="tab-pane active show" id="tab-ami" role="tabpanel">
            <x-tabler.card-body class="border-bottom bg-light-lt">
                <div class="row align-items-center g-2">
                    <div class="col">
                        <p class="text-muted mb-0 small">Berikut adalah daftar indikator yang sudah mengisi Evaluasi Diri. Klik <strong>"Isi"</strong> pada kolom AMI untuk melakukan audit, dan <strong>"Isi"</strong> pada kolom RTP untuk rencana perbaikan jika temuan KTS.</p>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-ami" />
                        <x-tabler.datatable-filter dataTableId="table-ami">
                            <div class="row g-2">
                                <div class="col-12">
                                    <x-tabler.form-select name="ami_hasil_akhir" label="Hasil AMI" class="mb-2">
                                        <option value="">Semua Hasil</option>
                                        <option value="empty">Belum Dinilai</option>
                                        <option value="0">KTS</option>
                                        <option value="1">Terpenuhi</option>
                                        <option value="2">Terlampaui</option>
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-12">
                                    <x-tabler.form-select name="ed_status" label="Status ED" class="mb-2">
                                        <option value="">Semua Status</option>
                                        <option value="isi">Sudah Isi</option>
                                        <option value="kosong">Belum Isi</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                        <x-tabler.datatable-search dataTableId="table-ami" />
                    </div>
                </div>
            </x-tabler.card-body>
            <x-tabler.card-body class="p-0 table-responsive">
                <x-tabler.datatable
                    id="table-ami"
                    route="{{ route('pemutu.ami.data', $periode->encrypted_periodespmi_id) }}"
                    :columns="[
                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                        ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Status ED', 'width' => '10%', 'class' => 'text-center', 'orderable' => false],
                        ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Status AMI', 'width' => '10%', 'class' => 'text-center', 'orderable' => false],
                        ['data' => 'rtp', 'name' => 'rtp', 'title' => 'RTP', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'AMI', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </x-tabler.card-body>
        </div>

        {{-- TAB 2: TINJAUAN EFEKTIVITAS --}}
        <div class="tab-pane" id="tab-te" role="tabpanel">
            <x-tabler.card-body class="border-bottom bg-light-lt">
                <p class="text-muted mb-0 small">Daftar indikator <strong>KTS (Ketidaksesuaian)</strong> dari periode tahun lalu. Auditor meninjau efektivitas tindakan perbaikan yang telah dilakukan.</p>
            </x-tabler.card-body>
            <x-tabler.card-body class="p-0 table-responsive">
                <x-tabler.datatable
                    id="table-te"
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
            </x-tabler.card-body>
        </div>
    </div>
</x-tabler.card>
@endif
@endsection
