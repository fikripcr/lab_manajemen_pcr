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
<x-tabler.card>
    <x-tabler.card-header class="bg-primary-lt" title="Detail Kegiatan">
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
                <p class="text-muted mb-0 small">Berikut adalah daftar indikator yang sudah mengisi Evaluasi Diri. Klik <strong>"Isi"</strong> pada kolom AMI untuk melakukan audit, dan <strong>"Isi"</strong> pada kolom RTP untuk rencana perbaikan jika temuan KTS.</p>
            </x-tabler.card-body>
            <x-tabler.card-body class="p-0 table-responsive">
                <x-tabler.datatable
                    id="table-ami"
                    route="{{ route('pemutu.ami.data', $periode->encrypted_periodespmi_id) }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_info', 'name' => 'indikator_info', 'title' => 'Indikator'],
                        ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Status ED', 'width' => '12%', 'class' => 'text-center', 'orderable' => false],
                        ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Status AMI', 'width' => '12%', 'class' => 'text-center', 'orderable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'AMI', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'rtp', 'name' => 'rtp', 'title' => 'RTP', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
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
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_info', 'name' => 'indikator_info', 'title' => 'Indikator'],
                        ['data' => 'rtp', 'name' => 'rtp', 'title' => 'Rencana (RTP)', 'width' => '20%'],
                        ['data' => 'ptp', 'name' => 'ptp', 'title' => 'Pelaksanaan (PTP)', 'width' => '20%'],
                        ['data' => 'te', 'name' => 'te', 'title' => 'Tinjauan (TE)', 'width' => '20%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </x-tabler.card-body>
        </div>
    </div>
</x-tabler.card>
@endsection
