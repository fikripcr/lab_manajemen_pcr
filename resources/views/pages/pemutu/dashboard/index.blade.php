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
    }
    .metric-card .card-body {
        padding: 1rem;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
        color: #1e293b;
    }
    .metric-title {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .metric-trend {
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    .eisenhower-box {
        color: white;
        text-align: center;
        padding: 1rem 0.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .eisenhower-box .title {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    .eisenhower-box .value {
        font-size: 1.75rem;
        font-weight: bold;
        line-height: 1.2;
    }
    .eisenhower-box .subtitle {
        font-size: 0.65rem;
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
        margin-bottom: 0.25rem;
        font-size: 0.7rem;
    }
    .hbar-label {
        width: 40px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-right: 0.5rem;
        font-weight: 500;
    }
    .hbar-wrapper {
        flex: 1;
        display: flex;
        height: 14px;
        background-color: #f1f5f9;
        border-radius: 2px;
        overflow: hidden;
    }
    .hbar-fill {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 4px;
        color: white;
        font-size: 0.65rem;
        font-weight: bold;
        line-height: 1;
    }
</style>
@endpush

@section('header')
<div class="container-xl mt-3">
    <div class="d-flex justify-content-between align-items-center mb-0">
        <h2 class="page-title text-primary fw-bold" style="font-size: 1.5rem; letter-spacing: -0.5px;">
            <span class="fst-italic me-2" style="color:#0f3f61;">SPMI</span> 
            <span class="text-secondary fw-normal">| Dashboard - Overview</span>
        </h2>
        <div class="btn-list">
            <button type="button" class="btn btn-primary d-none d-sm-inline-block">Overview</button>
            <button type="button" class="btn btn-outline-secondary">Penetapan</button>
            <button type="button" class="btn btn-outline-secondary">Pelaksanaan</button>
            <button type="button" class="btn btn-outline-secondary">Evaluasi</button>
            <button type="button" class="btn btn-outline-secondary">Pengendalian</button>
            <button type="button" class="btn btn-outline-secondary">Peningkatan</button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-xl">
    
    {{-- FILTERS ROW --}}
    <div class="card mb-3 metric-card">
        <div class="card-body py-2">
            <h5 class="card-title mb-2 text-dark fs-5 fw-bold">Filters</h5>
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
                            <option value="{{ $u->orgunit_id }}" {{ $currentUnit == $u->orgunit_id ? 'selected' : '' }}>{{ $u->name }}</option>
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
        </div>
    </div>

    <div class="row g-3">
        {{-- LEFT COLUMN: 6 KPI Cards --}}
        <div class="col-lg-4">
            <div class="row g-3">
                @php
                    $cards = [
                        ['id' => 'tercapai', 'title' => 'Indikator Tercapai (1 Tahun)'],
                        ['id' => 'tidak_tercapai', 'title' => 'Indikator Tidak Tercapai (1 Tahun)'],
                        ['id' => 'tingkatkan', 'title' => 'Status Tingkatkan'],
                        ['id' => 'penyesuaian', 'title' => 'Status Penyesuaian'],
                        ['id' => 'tetap', 'title' => 'Status Tetap'],
                        ['id' => 'nonaktif', 'title' => 'Status Nonaktif'],
                    ];
                @endphp
                @foreach($cards as $c)
                @php $m = $metrics[$c['id']]; @endphp
                <div class="col-6">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-title">{{ $c['title'] }}</div>
                            <div class="metric-value">{{ number_format($m['val']) }}</div>
                            <div class="metric-trend text-{{ $m['color'] }}">
                                @if($m['trend'] == 'up') <i class="ti ti-arrow-up"></i>
                                @elseif($m['trend'] == 'down') <i class="ti ti-arrow-down"></i>
                                @endif
                                {{ $m['pct'] }}% <span class="text-muted ms-1">From Last Year</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- MIDDLE COLUMN: Sparklines & Donut --}}
        <div class="col-lg-4">
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="card metric-card">
                        <div class="card-body d-flex flex-column">
                            <div class="metric-title">Total Indikator</div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="metric-value me-3">{{ end($trendData['indikator']) }}</div>
                            </div>
                            <div class="mt-auto">
                                <div id="sparkline-indikator" style="min-height: 40px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card metric-card">
                        <div class="card-body d-flex flex-column">
                            <div class="metric-title">Total Standar SPMI</div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="metric-value me-3">{{ end($trendData['standar']) }}</div>
                            </div>
                            <div class="mt-auto">
                                <div id="sparkline-standar" style="min-height: 40px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card metric-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Penetapan Jenis Kriteria</h5>
                    <div id="chart-kriteria" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Top/Bottom Bars & Eisenhower --}}
        <div class="col-lg-4">
            <div class="card metric-card mb-3">
                <div class="card-body p-2">
                    <div class="row g-2">
                        {{-- Top/Bottom Units --}}
                        <div class="col-6">
                            <h6 class="text-center fw-bold mb-2 pb-1 border-bottom" style="font-size:0.75rem;">Top 3 Unit/Prodi Tertinggi</h6>
                            @foreach($top3Units as $u)
                                <div class="hbar-row">
                                    <div class="hbar-label" title="{{ $u->unit_name }}">{{ $u->unit_name }}</div>
                                    <div class="hbar-wrapper">
                                        <div class="hbar-fill" style="width: {{ ($u->avg_skala / 5) * 100 }}%; background-color: #0ca678;">{{ round($u->avg_skala, 1) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-6">
                            <h6 class="text-center fw-bold mb-2 pb-1 border-bottom" style="font-size:0.75rem;">Top 3 Unit/Prodi Terendah</h6>
                            @foreach($bottom3Units as $u)
                                <div class="hbar-row">
                                    <div class="hbar-label" title="{{ $u->unit_name }}">{{ $u->unit_name }}</div>
                                    <div class="hbar-wrapper">
                                        <div class="hbar-fill" style="width: {{ ($u->avg_skala / 5) * 100 }}%; background-color: #d63939;">{{ round($u->avg_skala, 1) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="row g-2">
                        {{-- Top/Bottom Standar --}}
                        <div class="col-6">
                            <h6 class="text-center fw-bold mb-2 pb-1 border-bottom" style="font-size:0.75rem;">Top 3 Standar Tertinggi</h6>
                            @foreach($top3Standar as $s)
                                <div class="hbar-row">
                                    <div class="hbar-label" title="{{ $s->dokumen_name }}">{{ substr($s->dokumen_name, 0, 7) }}..</div>
                                    <div class="hbar-wrapper">
                                        <div class="hbar-fill" style="width: {{ ($s->avg_skala / 5) * 100 }}%; background-color: #0ca678;">{{ round($s->avg_skala, 1) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-6">
                            <h6 class="text-center fw-bold mb-2 pb-1 border-bottom" style="font-size:0.75rem;">Top 3 Standar Terendah</h6>
                            @foreach($bottom3Standar as $s)
                                <div class="hbar-row">
                                    <div class="hbar-label" title="{{ $s->dokumen_name }}">{{ substr($s->dokumen_name, 0, 7) }}..</div>
                                    <div class="hbar-wrapper">
                                        <div class="hbar-fill" style="width: {{ ($s->avg_skala / 5) * 100 }}%; background-color: #d63939;">{{ round($s->avg_skala, 1) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Eisenhower Matrix --}}
            <div class="card metric-card">
                <div class="card-body p-2">
                    <div class="row g-1 text-center border-bottom pb-1 mb-1" style="font-size: 0.7rem; font-weight: bold;">
                        <div class="col-3"></div>
                        <div class="col-4">Important</div>
                        <div class="col-5">Not Important</div>
                    </div>
                    <div class="row g-1 text-center" style="font-size: 0.7rem; font-weight: bold;">
                        <div class="col-3 d-flex flex-column">
                            <div class="flex-grow-1 border-end border-bottom d-flex align-items-center justify-content-center">Urgent</div>
                            <div class="flex-grow-1 border-end d-flex align-items-center justify-content-center">Not Urgent</div>
                        </div>
                        <div class="col-9">
                            <div class="row g-1 mb-1">
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #e63946;">
                                        <div class="title">Important / Urgent</div>
                                        <div class="value">{{ number_format($eisenhowerCount['important_urgent']) }}</div>
                                        <div class="subtitle">Indikator</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #457b9d;">
                                        <div class="title">Important / Not...</div>
                                        <div class="value">{{ number_format($eisenhowerCount['important_not_urgent']) }}</div>
                                        <div class="subtitle">Indikator</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1">
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #fca311;">
                                        <div class="title">Not Important /...</div>
                                        <div class="value">{{ number_format($eisenhowerCount['not_important_urgent']) }}</div>
                                        <div class="subtitle">Indikator</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="eisenhower-box" style="background-color: #a8dadc; color: #1d3557;">
                                        <div class="title">Not Important /...</div>
                                        <div class="value">{{ number_format($eisenhowerCount['not_important_not_urgent']) }}</div>
                                        <div class="subtitle">Indikator</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
