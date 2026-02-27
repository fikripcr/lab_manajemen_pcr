@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('content')
<div class="row">
    {{-- Summary Cards --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pemutu.indikator-summary.standar') }}">
                            <i class="ti ti-book me-2"></i>Indikator Standar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('pemutu.indikator-summary.performa') }}">
                            <i class="ti ti-chart-bar me-2"></i>Indikator Performa (KPI)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                {{-- Summary Cards --}}
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Indikator Performa</div>
                                </div>
                                <div class="h1 mb-3 text-primary">{{ number_format($totalIndikatorActive) }}</div>
                                <div class="text-muted small">Indikator performa untuk KPI Pegawai</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total KPI Aktif</div>
                                </div>
                                <div class="h1 mb-3 text-success">{{ number_format($kpiTotalPegawai ?? 0) }}</div>
                                <div class="text-muted small">Pegawai dengan KPI aktif</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Avg Score KPI</div>
                                </div>
                                <div class="h1 mb-3 text-info">{{ number_format($kpiAvgScore ?? 0, 1) }}</div>
                                <div class="text-muted small">Rata-rata skor KPI semua pegawai</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter Form --}}
                <form id="table-performa-filter" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="kelompok_indikator" id="performa-filter-kelompok">
                                <option value="">Semua Kelompok</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non Akademik">Non Akademik</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="year" id="performa-filter-year">
                                <option value="">Semua Tahun</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->tahun }}">{{ $periode->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" placeholder="Cari: No. Indikator / Nama Pegawai..." id="table-performa-search">
                                <button class="btn btn-outline-secondary" type="button" id="table-performa-clear-search" style="display: none;">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success btn-sm w-100" onclick="exportExcel()">
                                <i class="ti ti-file-export me-1"></i> Export
                            </button>
                        </div>
                    </div>
                    <div id="table-performa-active-filters" class="mt-2"></div>
                </form>

                {{-- DataTable --}}
                <x-tabler.datatable
                    id="table-performa"
                    route="{{ route('pemutu.indikator-summary.data-performa') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator Performa', 'width' => '30%'],
                        ['data' => 'parent_info', 'name' => 'parent_no_indikator', 'title' => 'Parent', 'width' => '10%'],
                        ['data' => 'labels', 'name' => 'all_labels', 'title' => 'Label', 'width' => '12%'],
                        ['data' => 'kpi_detail', 'name' => 'kpi_avg_score', 'title' => 'KPI Pegawai', 'width' => '20%'],
                        ['data' => 'kpi_score', 'name' => 'kpi_avg_score', 'title' => 'Avg Score', 'width' => '10%', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                    :options="[
                        'scrollX' => true,
                        'scrollCollapse' => true,
                        'fixedColumns' => ['leftColumns' => 2],
                        'order' => [[1, 'asc']],
                    ]"
                    ajax-load
                />
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportExcel() {
        const params = new URLSearchParams();
        const form = document.getElementById('table-performa-filter');
        if (form) {
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
        }
        params.append('type', 'performa');
        window.location.href = '{{ route('pemutu.indikator-summary.export') }}?' + params.toString();
    }
</script>
@endpush
@endsection
