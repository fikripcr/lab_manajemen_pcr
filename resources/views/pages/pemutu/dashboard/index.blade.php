@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu"/>
@endsection

@section('content')
{{-- Metrics Row --}}
<div class="row row-cards bg-white">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-primary-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-primary">Total Dokumen</div>
                </div>
                <div class="h1 mb-0 fw-bold text-primary">{{ $totalDokumen }}</div>
                <div class="text-primary opacity-50 small mt-2">Arsip Penjaminan Mutu</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-success-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-success">Total Indikator</div>
                </div>
                <div class="h1 mb-0 fw-bold text-success">{{ $totalIndikator }}</div>
                <div class="text-success opacity-50 small mt-2">Standar Mutu Internal</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-warning-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-warning">Total KPI</div>
                </div>
                <div class="h1 mb-0 fw-bold text-warning">{{ $totalKpi }}</div>
                <div class="text-warning fw-bold small mt-2">{{ $kpiAchievementRate }}% Submitted</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-purple-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-purple">Total Pegawai</div>
                </div>
                <div class="h1 mb-0 fw-bold text-purple">{{ $totalPegawai }}</div>
                <div class="text-purple opacity-50 small mt-2">Tim Penjaminan Mutu</div>
            </div>
        </div>
    </div>
</div>

{{-- Dashboard Charts (Phase 7) --}}
<div class="row row-cards mt-3">
    {{-- ED per Unit --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-chart-bar me-2 text-primary"></i> Rata-rata ED per Prodi</h3>
            </div>
            <div class="card-body">
                <div id="chart-ed-unit" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    
    {{-- AMI per Unit --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-chart-pie-2 me-2 text-warning"></i> Hasil AMI per Prodi</h3>
            </div>
            <div class="card-body">
                <div id="chart-ami-unit" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-3">
    {{-- Eisenhower Matrix --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-chart-scatter me-2 text-danger"></i> Matriks Pengendalian (Eisenhower)</h3>
            </div>
            <div class="card-body">
                <div id="chart-eisenhower" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>

    {{-- Status Pengendalian --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-chart-donut me-2 text-success"></i> Sebaran Status Pengendalian</h3>
            </div>
            <div class="card-body">
                <div id="chart-pengendalian" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline & Detailed Metrics --}}
<div class="row row-cards mt-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-history me-2 text-primary"></i> Timeline Periode SPMI</h3>
            </div>
            <div class="card-body">
                @if($activePeriodeSpmi)
                    <div class="mb-4 text-center p-3 bg-light rounded-3">
                        <div class="fw-bold fs-2 text-primary">{{ $activePeriodeSpmi->nama }}</div>
                        <div class="text-muted small">Periode: {{ $activePeriodeSpmi->periode }}</div>
                    </div>
                    
                    <ul class="timeline">
                        @php
                            $phases = [
                                ['label' => 'Penetapan', 'date' => $activePeriodeSpmi->penetapan_awal, 'end' => $activePeriodeSpmi->penetapan_akhir, 'icon' => 'ti ti-gavel', 'color' => 'primary'],
                                ['label' => 'Pelaksanaan', 'date' => $activePeriodeSpmi->penetapan_akhir, 'end' => $activePeriodeSpmi->ami_awal, 'icon' => 'ti ti-player-play', 'color' => 'secondary'],
                                ['label' => 'Evaluasi (AMI)', 'date' => $activePeriodeSpmi->ami_awal, 'end' => $activePeriodeSpmi->ami_akhir, 'icon' => 'ti ti-clipboard-check', 'color' => 'warning'],
                                ['label' => 'Pengendalian', 'date' => $activePeriodeSpmi->pengendalian_awal, 'end' => $activePeriodeSpmi->pengendalian_akhir, 'icon' => 'ti ti-settings-exclamation', 'color' => 'danger'],
                                ['label' => 'Peningkatan', 'date' => $activePeriodeSpmi->peningkatan_awal, 'end' => $activePeriodeSpmi->peningkatan_akhir, 'icon' => 'ti ti-trending-up', 'color' => 'success'],
                            ];
                            $now = now();
                        @endphp
                        
                        @foreach($phases as $phase)
                            @php
                                $start = \Carbon\Carbon::parse($phase['date']);
                                $end = $phase['end'] ? \Carbon\Carbon::parse($phase['end']) : null;
                                $isActive = $now->between($start, $end ?? $start->copy()->addDay());
                                $isPast = $now->gt($end ?? $start);
                            @endphp
                            <li class="timeline-event">
                                <div class="timeline-event-icon bg-{{ $phase['color'] }}">
                                    <i class="{{ $phase['icon'] }}"></i>
                                </div>
                                <div class="card timeline-event-card {{ $isActive ? 'border-primary shadow-sm' : 'border-0 shadow-none' }}">
                                    <div class="card-body p-2">
                                        <div class="fw-bold {{ $isActive ? 'text-primary' : '' }}">{{ $phase['label'] }}</div>
                                        <div class="text-secondary small">
                                            {{ $start->translatedFormat('d M Y') }} 
                                            @if($end) - {{ $end->translatedFormat('d M Y') }} @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center py-5">
                        <i class="ti ti-calendar-off fs-1 opacity-25 d-block mb-3"></i>
                        Tidak ada periode SPMI yang aktif saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-file-description me-2 text-primary"></i> Rincian Dokumen & Indikator</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-nowrap card-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Kebijakan --}}
                            <tr>
                                <td rowspan="5" class="bg-light fw-bold text-dark">Kebijakan</td>
                                <td>{{ pemutuJenisLabel('visi') }}</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['visi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>{{ pemutuJenisLabel('misi') }}</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['misi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>{{ pemutuJenisLabel('rjp') }}</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['rjp'] }}</span></td>
                            </tr>
                            <tr>
                                <td>{{ pemutuJenisLabel('renstra') }}</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['renstra'] }}</span></td>
                            </tr>
                            <tr>
                                <td>{{ pemutuJenisLabel('renop') }}</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['renop'] }}</span></td>
                            </tr>

                            {{-- Standar --}}
                            <tr>
                                <td rowspan="3" class="bg-blue-lt fw-bold text-primary">Standar</td>
                                <td>Standar SPMI</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['standar'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Manual Prosedur</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['manual_prosedur'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Formulir</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['formulir'] }}</span></td>
                            </tr>

                            {{-- Indikator --}}
                            <tr>
                                <td rowspan="3" class="bg-green-lt fw-bold text-success">Indikator</td>
                                <td>Indikator Standar</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $standarCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Renop</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $renopCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Performa</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $performaCount }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent KPI Submissions --}}
@if($recentKpi->count() > 0)
<div class="row row-cards mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengajuan KPI Terbaru</h3>
            </div>
            <x-tabler.datatable-client
                id="table-recent-kpi"
                :columns="[
                    ['name' => 'Pegawai'],
                    ['name' => 'Indikator'],
                    ['name' => 'Periode'],
                    ['name' => 'Status']
                ]"
            >
                @foreach($recentKpi as $kpi)
                <tr>
                    <td>{{ $kpi->pegawai->nama ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 300px;">{{ $kpi->indikator->indikator ?? '-' }}</td>
                    <td>{{ $kpi->semester }} {{ $kpi->year }}</td>
                    <td>
                        <span class="badge bg-{{ $kpi->status === 'approved' ? 'success' : 'info' }}-lt">
                            {{ ucfirst($kpi->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </x-tabler.datatable-client>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. ED per Unit Chart
    var edPerUnitData = @json($edPerUnit);
    var optionsEd = {
        chart: { type: 'bar', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Rata-rata Skala ED', data: edPerUnitData.data }],
        xaxis: { categories: edPerUnitData.categories, tooltip: { enabled: false } },
        colors: ['#206bc4'],
        plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, formatter: function (val) { return val; }, offsetY: -20, style: { fontSize: '12px', colors: ["#304758"] } }
    };
    if (edPerUnitData.data.length > 0) {
        new ApexCharts(document.getElementById('chart-ed-unit'), optionsEd).render();
    } else {
        document.getElementById('chart-ed-unit').innerHTML = '<div class="text-center text-muted py-5">Belum ada data skoring ED</div>';
    }

    // 2. AMI per Unit Chart
    var amiPerUnitData = @json($amiPerUnit);
    var optionsAmi = {
        chart: { type: 'bar', height: 300, stacked: true, fontFamily: 'inherit', toolbar: { show: false } },
        series: amiPerUnitData.series,
        xaxis: { categories: amiPerUnitData.categories },
        colors: ['#d63939', '#2fb344', '#17a2b8'], // KTS, Terpenuhi, Terlampaui
        plotOptions: { bar: { horizontal: false, borderRadius: 2 } },
        legend: { position: 'top', horizontalAlign: 'right' },
        fill: { opacity: 1 }
    };
    if (amiPerUnitData.categories.length > 0) {
        new ApexCharts(document.getElementById('chart-ami-unit'), optionsAmi).render();
    } else {
        document.getElementById('chart-ami-unit').innerHTML = '<div class="text-center text-muted py-5">Belum ada data AMI terisi</div>';
    }

    // 3. Eisenhower Chart
    var eisenhowerSeries = @json($eisenhowerSeries);
    var optionsEisenhower = {
        chart: { type: 'bubble', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Frekuensi Indikator', data: eisenhowerSeries }],
        xaxis: { title: { text: '' }, min: 0, max: 10, tickAmount: 10, labels: { formatter: (val) => val.toFixed(0) } },
        yaxis: { title: { text: '' }, min: 0, max: 10, tickAmount: 10, labels: { formatter: (val) => val.toFixed(0) } },
        fill: { opacity: 0.8 },
        colors: ['#f59f00'],
        dataLabels: { enabled: false },
        tooltip: { z: { title: 'Jumlah Indikator: ' } },
        annotations: {
            xaxis: [
                { x: 5, strokeDashArray: 0, borderColor: '#ccc', label: { text: 'Urgensi →', style: { color: '#666' } } }
            ],
            yaxis: [
                { y: 5, strokeDashArray: 0, borderColor: '#ccc', label: { text: 'Kepentingan →', style: { color: '#666' } } }
            ]
        }
    };
    if (eisenhowerSeries.length > 0) {
        new ApexCharts(document.getElementById('chart-eisenhower'), optionsEisenhower).render();
    } else {
        document.getElementById('chart-eisenhower').innerHTML = '<div class="text-center text-muted py-5">Belum ada skor prioritas pengendalian</div>';
    }

    // 4. Pengendalian Status Chart
    var pengendStatus = @json($pengendStatus);
    var optionsPengend = {
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        series: pengendStatus.series,
        labels: pengendStatus.labels,
        colors: ['#206bc4', '#f59f00', '#2fb344', '#d63939', '#6c757d'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, formatter: function (val, opts) {
            return opts.w.config.series[opts.seriesIndex]
        }}
    };
    
    if(pengendStatus.series.length > 0) {
        new ApexCharts(document.getElementById('chart-pengendalian'), optionsPengend).render();
    } else {
        document.getElementById('chart-pengendalian').innerHTML = '<div class="text-center text-muted py-5">Belum ada tindak lanjut pengendalian</div>';
    }
});
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. ED per Unit Chart
    var edPerUnitData = @json($edPerUnit);
    var optionsEd = {
        chart: { type: 'bar', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Rata-rata Skala ED', data: edPerUnitData.data }],
        xaxis: { categories: edPerUnitData.categories, tooltip: { enabled: false } },
        colors: ['#206bc4'],
        plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, formatter: function (val) { return val; }, offsetY: -20, style: { fontSize: '12px', colors: ["#304758"] } }
    };
    if (edPerUnitData.data.length > 0) {
        new ApexCharts(document.getElementById('chart-ed-unit'), optionsEd).render();
    } else {
        document.getElementById('chart-ed-unit').innerHTML = '<div class="text-center text-muted py-5">Belum ada data skoring ED</div>';
    }

    // 2. AMI per Unit Chart
    var amiPerUnitData = @json($amiPerUnit);
    var optionsAmi = {
        chart: { type: 'bar', height: 300, stacked: true, fontFamily: 'inherit', toolbar: { show: false } },
        series: amiPerUnitData.series,
        xaxis: { categories: amiPerUnitData.categories },
        colors: ['#d63939', '#2fb344', '#17a2b8'], // KTS, Terpenuhi, Terlampaui
        plotOptions: { bar: { horizontal: false, borderRadius: 2 } },
        legend: { position: 'top', horizontalAlign: 'right' },
        fill: { opacity: 1 }
    };
    if (amiPerUnitData.categories.length > 0) {
        new ApexCharts(document.getElementById('chart-ami-unit'), optionsAmi).render();
    } else {
        document.getElementById('chart-ami-unit').innerHTML = '<div class="text-center text-muted py-5">Belum ada data AMI terisi</div>';
    }

    // 3. Eisenhower Chart
    var eisenhowerSeries = @json($eisenhowerSeries);
    var optionsEisenhower = {
        chart: { type: 'bubble', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Frekuensi Indikator', data: eisenhowerSeries }],
        xaxis: { title: { text: '' }, min: 0, max: 10, tickAmount: 10, labels: { formatter: (val) => val.toFixed(0) } },
        yaxis: { title: { text: '' }, min: 0, max: 10, tickAmount: 10, labels: { formatter: (val) => val.toFixed(0) } },
        fill: { opacity: 0.8 },
        colors: ['#f59f00'],
        dataLabels: { enabled: false },
        tooltip: { z: { title: 'Jumlah Indikator: ' } },
        annotations: {
            xaxis: [
                { x: 5, strokeDashArray: 0, borderColor: '#ccc', label: { text: 'Urgensi →', style: { color: '#666' } } }
            ],
            yaxis: [
                { y: 5, strokeDashArray: 0, borderColor: '#ccc', label: { text: 'Kepentingan →', style: { color: '#666' } } }
            ]
        }
    };
    if (eisenhowerSeries.length > 0) {
        new ApexCharts(document.getElementById('chart-eisenhower'), optionsEisenhower).render();
    } else {
        document.getElementById('chart-eisenhower').innerHTML = '<div class="text-center text-muted py-5">Belum ada skor prioritas pengendalian</div>';
    }

    // 4. Pengendalian Status Chart
    var pengendStatus = @json($pengendStatus);
    var optionsPengend = {
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        series: pengendStatus.series,
        labels: pengendStatus.labels,
        colors: ['#206bc4', '#f59f00', '#2fb344', '#d63939', '#6c757d'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, formatter: function (val, opts) {
            return opts.w.config.series[opts.seriesIndex]
        }}
    };
    
    if(pengendStatus.series.length > 0) {
        new ApexCharts(document.getElementById('chart-pengendalian'), optionsPengend).render();
    } else {
        document.getElementById('chart-pengendalian').innerHTML = '<div class="text-center text-muted py-5">Belum ada tindak lanjut pengendalian</div>';
    }
});
</script>
@endpush
