@extends('layouts.tabler.app')
@section('title', $pageTitle)

@push('styles')
<style>
    /* PowerBI Inspired Custom Utilities */
    .metric-card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eef2f6;
        height: 100%;
        transition: transform 0.2s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-card .card-body {
        padding: 0.85rem;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #1e293b;
    }
    .metric-title {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .metric-trend {
        font-size: 0.7rem;
        margin-top: 0.25rem;
    }
    .eisenhower-box {
        color: white;
        text-align: center;
        padding: 1.5rem 0.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
    }
    .eisenhower-box .title {
        font-size: 0.85rem;
        opacity: 0.9;
        font-weight: 600;
    }
    .eisenhower-box .value {
        font-size: 2.25rem;
        font-weight: bold;
        line-height: 1.2;
    }
    .eisenhower-box .subtitle {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    .form-col-filter label {
        font-size: 0.75rem;
        font-weight: bold;
        color: #1e293b;
    }
    /* Horizontal Bar Row */
    .hbar-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.4rem;
        font-size: 0.75rem;
    }
    .hbar-label {
        width: 60px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-right: 0.5rem;
        font-weight: 500;
    }
    .hbar-wrapper {
        flex: 1;
        display: flex;
        height: 18px;
        background-color: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
    }
    .hbar-fill {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 6px;
        color: white;
        font-size: 0.7rem;
        font-weight: bold;
        line-height: 1;
    }
    /* Custom divider */
    .v-divider {
        width: 1px;
        background-color: #eef2f6;
        margin: 0 0.5rem;
    }
</style>
@endpush

@section('header')
<x-tabler.page-header title="Dashboard Pemutu">
    <x-slot:actions>
        <button type="button" class="btn btn-primary d-none d-sm-inline-block">Overview</button>
        <button type="button" class="btn btn-outline-secondary">Penetapan</button>
        <button type="button" class="btn btn-outline-secondary">Pelaksanaan</button>
        <button type="button" class="btn btn-outline-secondary">Evaluasi</button>
        <button type="button" class="btn btn-outline-secondary">Pengendalian</button>
        <button type="button" class="btn btn-outline-secondary">Peningkatan</button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @if($pendingApprovalsCount > 0)
    <div class="alert alert-warning alert-important alert-dismissible" role="alert">
        <div class="d-flex">
            <div>
                <i class="ti ti-alert-circle icon alert-icon"></i>
            </div>
            <div>
                <h4 class="alert-title">Perhatian!</h4>
                <div class="text-secondary">Ada {{ $pendingApprovalsCount }} dokumen yang menunggu persetujuan Anda.</div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
    @endif

    {{-- FILTERS ROW --}}
    <x-tabler.card class="mb-3 metric-card">
        <x-tabler.card-body class="py-2">
            <form method="GET" action="{{ route('pemutu.dashboard') }}" class="row g-3" id="filter-form">
                <div class="col-md-3 form-col-filter">
                    <label class="form-label mb-1">Tahun</label>
                    <x-tabler.form-select name="year" class="form-select-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ $currentYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-md-3 form-col-filter">
                    <label class="form-label mb-1">Bidang, Unit/Prodi</label>
                    <x-tabler.form-select name="unit_id" class="form-select-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All</option>
                        @foreach($units as $u)
                            <option value="{{ $u->encrypted_org_unit_id }}" {{ $currentUnit == $u->encrypted_org_unit_id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-md-3 form-col-filter">
                    <label class="form-label mb-1">Kriteria Standar</label>
                    <x-tabler.form-select name="kriteria" class="form-select-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All</option>
                        @foreach($kriterias as $k)
                            <option value="{{ $k }}" {{ $currentKriteria == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-md-3 form-col-filter d-flex align-items-end">
                    <a href="{{ route('pemutu.dashboard') }}" class="btn btn-sm btn-light w-100">Reset Filters</a>
                </div>
            </form>
        </x-tabler.card-body>
    </x-tabler.card>

    {{-- TOP ROW: 8 KPI CARDS (2 rows of 4) --}}
    <div class="row g-3 mb-3">
        {{-- Row 1: Totals & Primary Metrics --}}
        <div class="col-md-3">
            <x-tabler.card class="metric-card">
                <x-tabler.card-body class="d-flex flex-column">
                    <div class="metric-title">Total Indikator</div>
                    <div class="d-flex align-items-center mb-1">
                        <div class="metric-value me-3">{{ end($trendData['indikator']) }}</div>
                    </div>
                    <div class="mt-auto">
                        <div id="sparkline-indikator" style="min-height: 35px;"></div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        <div class="col-md-3">
            <x-tabler.card class="metric-card">
                <x-tabler.card-body class="d-flex flex-column">
                    <div class="metric-title">Total Standar SPMI</div>
                    <div class="d-flex align-items-center mb-1">
                        <div class="metric-value me-3">{{ end($trendData['standar']) }}</div>
                    </div>
                    <div class="mt-auto">
                        <div id="sparkline-standar" style="min-height: 35px;"></div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        @php
            $topMetrics = [
                ['id' => 'tercapai', 'title' => 'Indikator Tercapai (1 Thn)'],
                ['id' => 'tidak_tercapai', 'title' => 'Indikator Tidak Tercapai (1 Thn)'],
            ];
        @endphp
        @foreach($topMetrics as $c)
        @php $m = $metrics[$c['id']]; @endphp
        <div class="col-md-3">
            <x-tabler.card class="metric-card">
                <x-tabler.card-body>
                    <div class="metric-title">{{ $c['title'] }}</div>
                    <div class="metric-value">{{ number_format($m['val']) }}</div>
                    <div class="metric-trend text-{{ $m['color'] }}">
                        @if($m['trend'] == 'up') <i class="ti ti-arrow-up"></i>
                        @elseif($m['trend'] == 'down') <i class="ti ti-arrow-down"></i>
                        @endif
                        {{ $m['pct'] }}% <span class="text-muted ms-1">vs Last Year</span>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        @endforeach

        {{-- Row 2: Status States --}}
        @php
            $statusMetrics = [
                ['id' => 'tingkatkan', 'title' => 'Status Tingkatkan'],
                ['id' => 'penyesuaian', 'title' => 'Status Penyesuaian'],
                ['id' => 'tetap', 'title' => 'Status Tetap'],
                ['id' => 'nonaktif', 'title' => 'Status Nonaktif'],
            ];
        @endphp
        @foreach($statusMetrics as $c)
        @php $m = $metrics[$c['id']]; @endphp
        <div class="col-md-3">
            <x-tabler.card class="metric-card">
                <x-tabler.card-body>
                    <div class="metric-title">{{ $c['title'] }}</div>
                    <div class="metric-value">{{ number_format($m['val']) }}</div>
                    <div class="metric-trend text-{{ $m['color'] }}">
                        @if($m['trend'] == 'up') <i class="ti ti-arrow-up"></i>
                        @elseif($m['trend'] == 'down') <i class="ti ti-arrow-down"></i>
                        @endif
                        {{ $m['pct'] }}% <span class="text-muted ms-1">vs Last Year</span>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        @endforeach
    </div>

    {{-- MIDDLE ROW: Charts & Rankings --}}
    <div class="row g-3 mb-3">
        {{-- Donut Chart Column --}}
        <div class="col-lg-4">
            <x-tabler.card class="metric-card">
                <x-tabler.card-header title="Penetapan Jenis Kriteria" />
                <x-tabler.card-body>
                    <div id="chart-kriteria" style="min-height: 280px;"></div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- Unit Rankings Column --}}
        <div class="col-lg-4">
            <x-tabler.card class="metric-card">
                <x-tabler.card-header title="Top 3 Unit/Prodi" />
                <x-tabler.card-body>
                    <div class="mb-4">
                        <div class="text-success fw-bold mb-2" style="font-size: 0.7rem;">TERTINGGI</div>
                        @foreach($top3Units as $u)
                            <div class="hbar-row">
                                <div class="hbar-label" title="{{ $u->unit_name }}">{{ $u->unit_name }}</div>
                                <div class="hbar-wrapper">
                                    <div class="hbar-fill" style="width: {{ ($u->avg_skala / 5) * 100 }}%; background-color: #0ca678;">{{ round($u->avg_skala, 1) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="text-danger fw-bold mb-2" style="font-size: 0.7rem;">TERENDAH</div>
                        @foreach($bottom3Units as $u)
                            <div class="hbar-row">
                                <div class="hbar-label" title="{{ $u->unit_name }}">{{ $u->unit_name }}</div>
                                <div class="hbar-wrapper">
                                    <div class="hbar-fill" style="width: {{ ($u->avg_skala / 5) * 100 }}%; background-color: #d63939;">{{ round($u->avg_skala, 1) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- Standar Rankings Column --}}
        <div class="col-lg-4">
            <x-tabler.card class="metric-card">
                <x-tabler.card-header title="Top 3 Standar" />
                <x-tabler.card-body>
                    <div class="mb-4">
                        <div class="text-success fw-bold mb-2" style="font-size: 0.7rem;">TERTINGGI</div>
                        @foreach($top3Standar as $s)
                            <div class="hbar-row">
                                <div class="hbar-label" title="{{ $s->dokumen_name }}">{{ substr($s->dokumen_name, 0, 10) }}..</div>
                                <div class="hbar-wrapper">
                                    <div class="hbar-fill" style="width: {{ ($s->avg_skala / 5) * 100 }}%; background-color: #0ca678;">{{ round($s->avg_skala, 1) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="text-danger fw-bold mb-2" style="font-size: 0.7rem;">TERENDAH</div>
                        @foreach($bottom3Standar as $s)
                            <div class="hbar-row">
                                <div class="hbar-label" title="{{ $s->dokumen_name }}">{{ substr($s->dokumen_name, 0, 10) }}..</div>
                                <div class="hbar-wrapper">
                                    <div class="hbar-fill" style="width: {{ ($s->avg_skala / 5) * 100 }}%; background-color: #d63939;">{{ round($s->avg_skala, 1) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>

    {{-- BOTTOM ROW: Eisenhower Matrix --}}
    <div class="row">
        <div class="col-12">
            <x-tabler.card class="metric-card">
                <x-tabler.card-header title="Prioritas Pengendalian (Eisenhower Matrix)" />
                <x-tabler.card-body>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #e63946;">
                                        <div class="title">Important / Urgent</div>
                                        <div class="value">{{ number_format($eisenhowerCount['important_urgent']) }}</div>
                                        <div class="subtitle">Indikator Terdeteksi</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #457b9d;">
                                        <div class="title">Important / Not Urgent</div>
                                        <div class="value">{{ number_format($eisenhowerCount['important_not_urgent']) }}</div>
                                        <div class="subtitle">Indikator Terdeteksi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #fca311;">
                                        <div class="title">Not Important / Urgent</div>
                                        <div class="value">{{ number_format($eisenhowerCount['not_important_urgent']) }}</div>
                                        <div class="subtitle">Indikator Terdeteksi</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #a8dadc; color: #1d3557;">
                                        <div class="title">Not Important / Not Urgent</div>
                                        <div class="value">{{ number_format($eisenhowerCount['not_important_not_urgent']) }}</div>
                                        <div class="subtitle">Indikator Terdeteksi</div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sparkline Indikator
    var trendData = @json($trendData);
    if(trendData.years.length > 0) {
        new ApexCharts(document.getElementById('sparkline-indikator'), {
            chart: { type: 'area', height: 80, sparkline: { enabled: true } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.3 },
            series: [{ name: 'Total Indikator', data: trendData.indikator }],
            labels: trendData.years,
            colors: ['#a55eea']
        }).render();

        new ApexCharts(document.getElementById('sparkline-standar'), {
            chart: { type: 'area', height: 80, sparkline: { enabled: true } },
            stroke: { curve: 'step', width: 2 },
            fill: { opacity: 0.3 },
            series: [{ name: 'Total Standar SPMI', data: trendData.standar }],
            labels: trendData.years,
            colors: ['#457b9d']
        }).render();
    }

    // Donut Chart Penetapan Jenis Kriteria
    var kriteriaRaw = @json($jenisKriteriaRaw);
    if(kriteriaRaw.length > 0) {
        var labels = kriteriaRaw.map(v => v.label);
        var series = kriteriaRaw.map(v => parseInt(v.total));
        new ApexCharts(document.getElementById('chart-kriteria'), {
            chart: { type: 'donut', height: 260 },
            series: series,
            labels: labels,
            plotOptions: {
                pie: {
                    donut: { size: '55%' }
                }
            },
            dataLabels: { 
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(2) + "%"
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                show: true,
                fontSize: '11px',
                markers: { width: 8, height: 8 }
            }
        }).render();
    } else {
        document.getElementById('chart-kriteria').innerHTML = '<div class="text-center text-muted py-5">Belum ada data kriteria</div>';
    }
});
</script>
@endpush
