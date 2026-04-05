@extends('layouts.tabler.app')
@section('title', $pageTitle)

@push('styles')
<style>
    /* Status Summary Card - Reference Image Style */
    .status-summary-card {
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: none;
        background: #ffffff;
    }
    .status-summary-card .card-header .badge {
        font-size: 0.65rem;
        background: #f0fdf4 !important;
        color: #15803d !important;
        border: 1px solid #dcfce7;
    }
    .status-segment {
        padding: 0 1.5rem;
    }
    .status-segment:not(:last-child) {
        border-right: 1px solid #f1f5f9;
    }
    .status-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .status-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        color: #1e293b;
    }
    .status-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    .status-delta {
        font-size: 0.8rem;
        font-weight: 600;
    }



    /* Hierarchy Connecting Lines & Roadmap */
    .roadmap-container { 
        padding-left: 24px; 
        position: relative; 
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
    }
    .roadmap-item { position: relative; margin-bottom: 12px; }
    .roadmap-branch {
        position: absolute;
        left: -32px;
        top: 24px;
        bottom: -15px;
        width: 2px;
        border-left: 2px solid #e2e8f0;
    }
    .roadmap-line-horizontal {
        position: absolute;
        left: -32px;
        top: 24px;
        width: 32px;
        height: 2px;
        border-top: 2px solid #e2e8f0;
        border-top-left-radius: 8px;
    }
    .roadmap-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .roadmap-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .status-badge-premium {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        letter-spacing: 0.025em;
    }
    /* SVG Connector Path for curved lines if desired, using simple border for now */
</style>
@endpush

@section('header')
<x-tabler.page-header title="Dashboard Pemutu">
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

    <div class="tab-content">
        {{-- TAB 1: OVERVIEW KINERJA --}}
        <div class="tab-pane active show" id="tab-overview" role="tabpanel">

            {{-- TOP ROW: KPI CARDS & AMI SUMMARY --}}
            <div class="row g-3 mb-4">
                {{-- Totals Combined --}}
                <div class="col-md-4">
                    <x-tabler.card class="status-summary-card h-100">
                        <x-tabler.card-body class="d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center mb-4">
                                <div class="avatar bg-primary-lt me-4 avatar-xl shadow-sm"><i class="ti ti-target fs-1"></i></div>
                                <div>
                                    <div class="status-label">Ringkasan Penetapan (Standar & Indikator)</div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="status-value me-2">{{ number_format(end($trendData['indikator'])) }}</span>
                                        <span class="text-muted fw-normal">Indikator Unik</span>
                                    </div>
                                    <div class="text-muted smaller">
                                        <i class="ti ti-activity me-1"></i>
                                        Siklus {{ $currentYear }} • {{ number_format(end($trendData['standar'])) }} Standar SPMI
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto py-2 px-3 bg-light rounded d-flex justify-content-between">
                                <small class="fw-bold">Prioritas Pengendalian</small>
                                <span class="badge bg-primary text-white">{{ $metrics['tingkatkan']['val'] + $metrics['penyesuaian']['val'] }} Item</span>
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                {{-- Status Audit Summary Reference Card --}}
                <div class="col-md-8">
                    <x-tabler.card class="status-summary-card h-100 overflow-hidden">
                        <x-tabler.card-header class="d-flex justify-content-between align-items-center bg-white border-0 py-3">
                            <div>
                                <h3 class="card-title fw-bold mb-0">Ringkasan Status Audit Mutu Internal (AMI)</h3>
                                <p class="text-muted smaller mb-0">Update Real-time Siklus Audit {{ $currentYear }}</p>
                            </div>
                            <span class="badge bg-success-lt text-success border border-success-lt px-3 py-1">
                                <span class="p-1 bg-success rounded-circle me-2 d-inline-block"></span>LIVE MONITORING
                            </span>
                        </x-tabler.card-header>
                        <x-tabler.card-body class="pt-2 pb-4">
                            <div class="row align-items-center text-center text-md-start">
                                {{-- Terpenuhi --}}
                                <div class="col-md-4 status-segment">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="status-icon-box bg-success-lt text-success me-md-3 mb-3 mb-md-0 shadow-sm">
                                            <i class="ti ti-circle-check"></i>
                                        </div>
                                        <div>
                                            <div class="status-label">Jumlah Terpenuhi</div>
                                            <div class="d-flex align-items-baseline justify-content-center justify-content-md-start">
                                                <span class="status-value me-2">{{ number_format($metrics['tercapai']['val']) }}</span>
                                                @php $diff = $metrics['tercapai']['diff']; @endphp
                                                <span class="status-delta text-{{ $diff >= 0 ? 'success' : 'danger' }}">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }} <small>unit</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Terlampaui --}}
                                <div class="col-md-4 status-segment">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="status-icon-box bg-blue-lt text-primary me-md-3 mb-3 mb-md-0 shadow-sm">
                                            <i class="ti ti-sparkles"></i>
                                        </div>
                                        <div>
                                            <div class="status-label">Melampaui Standar</div>
                                            <div class="d-flex align-items-baseline justify-content-center justify-content-md-start">
                                                <span class="status-value me-2">{{ number_format($metrics['tetap']['val'] ?? 0) }}</span>
                                                @php $diff = $metrics['tetap']['diff'] ?? 0; @endphp
                                                <span class="status-delta text-{{ $diff >= 0 ? 'success' : 'danger' }}">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }} <small>unit</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- KTS --}}
                                <div class="col-md-4 status-segment">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="status-icon-box bg-danger-lt text-danger me-md-3 mb-3 mb-md-0 shadow-sm">
                                            <i class="ti ti-alert-triangle"></i>
                                        </div>
                                        <div>
                                            <div class="status-label">Ketidaksesuaian (KTS)</div>
                                            <div class="d-flex align-items-baseline justify-content-center justify-content-md-start">
                                                <span class="status-value me-2">{{ number_format($metrics['tidak_tercapai']['val']) }}</span>
                                                @php $diff = $metrics['tidak_tercapai']['diff']; @endphp
                                                <span class="status-delta text-{{ $diff <= 0 ? 'success' : 'danger' }}">
                                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }} <small>unit</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top text-center">
                                <a href="#" class="text-muted smaller fw-bold text-decoration-none">
                                    Buka Portal AMI Untuk Analisis Lebih Dalam <i class="ti ti-external-link ms-1"></i>
                                </a>
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>

            {{-- MIDDLE ROW: ANALISIS PER UNIT KERJA (Moved from Tab 3) --}}
            <div class="row g-3 mb-3">
                <div class="col-lg-12">
                    <x-tabler.card class="metric-card">
                        <x-tabler.card-header title="<i class='ti ti-report-analytics me-2'></i>Rata-Rata Skala Ketercapaian (Evaluasi Diri)" />
                        <x-tabler.card-body>
                            <div id="chart-unit-ed" style="min-height: 300px;"></div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
                <div class="col-lg-12">
                    <x-tabler.card class="metric-card">
                        <x-tabler.card-header title="<i class='ti ti-circle-check me-2'></i>Persentase Ketercapaian Indikator (Hasil Audit)" />
                        <x-tabler.card-body>
                            <div id="chart-unit-ami" style="min-height: 300px;"></div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>

            {{-- MIDDLE ROW: Strategic Goals & Eisenhower Matrix --}}
            <div class="row g-3 mb-4">
                {{-- Strategic Goals (Visi & Misi) --}}
                <div class="col-md-6">
                    <x-tabler.card class="metric-card h-100">
                        <x-tabler.card-header class="bg-white py-3 border-0">
                            <div>
                                <h3 class="card-title fw-bold mb-0">Capaian Dokumen Utama (Kebijakan)</h3>
                                <p class="text-muted smaller mb-0">Klik kartu untuk melihat rincian di Rekap Capaian</p>
                            </div>
                        </x-tabler.card-header>
                        <x-tabler.card-body class="pt-0">
                            <div class="row g-3">
                                {{-- AVG MISI --}}
                                <div class="col-12">
                                    <a href="{{ route('pemutu.dokumen.summary', ['jenis' => 'misi']) }}" class="text-decoration-none h-100 d-block">
                                        <div class="p-3 border rounded roadmap-card d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-blue-lt me-3 shadow-sm"><i class="ti ti-rocket"></i></div>
                                                <div>
                                                    <div class="text-uppercase text-muted smaller fw-bold ls-1">Rata-Rata MISI</div>
                                                    <div class="fw-bold text-dark fs-3">Seluruh Misi Univ.</div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold fs-2 text-{{ $avgMisiRate >= 80 ? 'success' : ($avgMisiRate >= 50 ? 'warning' : 'danger') }}">{{ $avgMisiRate }}%</div>
                                                <div class="progress progress-xs mt-1" style="width: 80px;">
                                                    <div class="progress-bar bg-{{ $avgMisiRate >= 80 ? 'success' : ($avgMisiRate >= 50 ? 'warning' : 'danger') }}" style="width: {{ $avgMisiRate }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                {{-- Individual VISI --}}
                                @foreach($visiStats as $visi)
                                <div class="col-12">
                                    <a href="{{ route('pemutu.dokumen.summary', ['jenis' => 'visi', 'id' => $visi['id']]) }}" class="text-decoration-none h-100 d-block">
                                        <div class="p-3 border rounded roadmap-card d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary-lt me-3 shadow-sm"><i class="ti ti-eye"></i></div>
                                                <div>
                                                    <div class="text-uppercase text-muted smaller fw-bold ls-1">CAPAIAN VISI</div>
                                                    <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ $visi['judul'] }}</div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold fs-2 text-{{ $visi['stats']['rate'] >= 80 ? 'success' : ($visi['stats']['rate'] >= 50 ? 'warning' : 'danger') }}">{{ $visi['stats']['rate'] }}%</div>
                                                <div class="progress progress-xs mt-1" style="width: 80px;">
                                                    <div class="progress-bar bg-{{ $visi['stats']['rate'] >= 80 ? 'success' : ($visi['stats']['rate'] >= 50 ? 'warning' : 'danger') }}" style="width: {{ $visi['stats']['rate'] }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                {{-- Eisenhower Matrix --}}
                <div class="col-md-6">
                    <x-tabler.card class="metric-card h-100">
                        <x-tabler.card-header class="bg-white py-3 border-0">
                            <div>
                                <h3 class="card-title fw-bold mb-0">Prioritas Pengendalian (Matrix)</h3>
                                <p class="text-muted smaller mb-0">Distribusi Urgensi & Signifikansi Strategis</p>
                            </div>
                        </x-tabler.card-header>
                        <x-tabler.card-body class="pt-0">
                            <div class="row g-3 h-100">
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded roadmap-card d-flex flex-column align-items-center text-center h-100">
                                        <div class="avatar bg-danger-lt mb-2"><i class="ti ti-alert-circle"></i></div>
                                        <div class="fs-1 fw-bold text-dark">{{ number_format($eisenhowerCount['important_urgent']) }}</div>
                                        <div class="text-uppercase text-muted smaller fw-bold ls-1 mt-1">Penting & Mendesak</div>
                                        <div class="text-muted smaller mt-auto">Tindakan Segera</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded roadmap-card d-flex flex-column align-items-center text-center h-100">
                                        <div class="avatar bg-blue-lt mb-2"><i class="ti ti-calendar"></i></div>
                                        <div class="fs-1 fw-bold text-dark">{{ number_format($eisenhowerCount['important_not_urgent']) }}</div>
                                        <div class="text-uppercase text-muted smaller fw-bold ls-1 mt-1">Penting, Tdk Mendesak</div>
                                        <div class="text-muted smaller mt-auto">Penjadwalan</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded roadmap-card d-flex flex-column align-items-center text-center h-100">
                                        <div class="avatar bg-warning-lt mb-2"><i class="ti ti-users"></i></div>
                                        <div class="fs-1 fw-bold text-dark">{{ number_format($eisenhowerCount['not_important_urgent']) }}</div>
                                        <div class="text-uppercase text-muted smaller fw-bold ls-1 mt-1">Tdk Penting, Mendesak</div>
                                        <div class="text-muted smaller mt-auto">Delegasikan</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded roadmap-card d-flex flex-column align-items-center text-center h-100">
                                        <div class="avatar bg-success-lt mb-2"><i class="ti ti-trash"></i></div>
                                        <div class="fs-1 fw-bold text-dark">{{ number_format($eisenhowerCount['not_important_not_urgent']) }}</div>
                                        <div class="text-uppercase text-muted smaller fw-bold ls-1 mt-1">Tdk Penting, Tdk Mendesak</div>
                                        <div class="text-muted smaller mt-auto">Eliminasi</div>
                                    </div>
                                </div>
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Rata-Rata Skala ED & AMI Ketercapaian
    var unitChartData = @json($unitChartData ?? null);
    if (unitChartData && unitChartData.categories.length > 0) {
        // 1. Chart Skala ED (Column)
        new ApexCharts(document.querySelector("#chart-unit-ed"), {
            series: [{ name: 'Rata-rata Skala ED', data: unitChartData.ed_series }],
            chart: { height: 300, type: 'bar', toolbar: { show: false } },
            colors: ['#206bc4'],
            plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
            dataLabels: { enabled: true, offsetY: -20, style: { fontSize: '12px', colors: ["#304758"] } },
            labels: unitChartData.categories,
            xaxis: { labels: { rotate: -45, trim: true, minHeight: 80 } },
            yaxis: { title: { text: 'Skala ED (0-4)' }, min: 0, max: 4, tickAmount: 4 }
        }).render();

        // 2. Chart AMI (Stacked)
        new ApexCharts(document.querySelector("#chart-unit-ami"), {
            series: [
                { name: 'KTS (Tidak Tercapai)', type: 'column', data: unitChartData.ami_kts },
                { name: 'Terpenuhi', type: 'column', data: unitChartData.ami_terpenuhi },
                { name: 'Terlampaui', type: 'column', data: unitChartData.ami_terlampaui },
                { name: 'Total Ketercapaian (%)', type: 'line', data: unitChartData.ami_series }
            ],
            chart: { height: 300, type: 'line', stacked: true, toolbar: { show: false } },
            colors: ['#d63939', '#2fb344', '#1d48b5', '#f59f00'],
            stroke: { width: [0, 0, 0, 3], curve: 'smooth' },
            plotOptions: { bar: { columnWidth: '50%', borderRadius: 2 } },
            markers: { size: [0, 0, 0, 4] },
            dataLabels: { enabled: true, enabledOnSeries: [3], formatter: function (val) { return val + "%" } },
            labels: unitChartData.categories,
            xaxis: { labels: { rotate: -45, trim: true, minHeight: 80 } },
            yaxis: [{ title: { text: 'Jumlah Indikator' }, min: 0 }, { opposite: true, title: { text: '% Ketercapaian' }, min: 0, max: 100, tickAmount: 5 }],
            legend: { 
                position: 'top', 
                horizontalAlign: 'center',
                fontSize: '12px',
                itemMargin: { horizontal: 10, vertical: 0 }
            },
            tooltip: { shared: true, intersect: false, y: { formatter: function (y, { seriesIndex }) { return seriesIndex === 3 ? y.toFixed(1) + "%" : y + " Indikator"; } } }
        }).render();
    }
});
</script>
@endpush

