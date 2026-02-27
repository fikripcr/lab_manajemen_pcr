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
                        <a class="nav-link active" href="{{ route('pemutu.indikator-summary.standar') }}">
                            <i class="ti ti-book me-2"></i>Indikator Standar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pemutu.indikator-summary.performa') }}">
                            <i class="ti ti-chart-bar me-2"></i>Indikator Performa (KPI)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                {{-- Summary Cards --}}
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Indikator Standar</div>
                                </div>
                                <div class="h1 mb-3">{{ number_format($totalIndikatorActive) }}</div>
                                <div class="d-flex mb-2">
                                    <div class="text-muted">Dari {{ $totalIndikator }} total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">ED Filled</div>
                                </div>
                                <div class="h1 mb-3 text-success">{{ number_format($edFilledUnits) }}</div>
                                <div class="progress progress-sm">
                                    @php $edProgress = $edTotalUnits > 0 ? round(($edFilledUnits / $edTotalUnits) * 100) : 0; @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $edProgress }}%"></div>
                                </div>
                                <div class="text-muted small">{{ $edProgress }}% dari {{ $edTotalUnits }} unit</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">AMI Assessed</div>
                                </div>
                                <div class="h1 mb-3 text-primary">{{ number_format($amiAssessed) }}</div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-danger-lt">{{ $amiKts }} KTS</span>
                                    <span class="badge bg-success-lt">{{ $amiTerpenuhi }} Terpenuhi</span>
                                    <span class="badge bg-info-lt">{{ $amiTerlampaui }} Terlampaui</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Pengendalian</div>
                                </div>
                                <div class="h1 mb-3 text-info">{{ number_format($pengendFilled) }}</div>
                                <div class="text-muted small">Unit dengan pengendalian aktif</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter Form --}}
                <form id="table-standar-filter" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="kelompok_indikator" id="standar-filter-kelompok">
                                <option value="">Semua Kelompok</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non Akademik">Non Akademik</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="year" id="standar-filter-year">
                                <option value="">Semua Tahun</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->tahun }}">{{ $periode->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" placeholder="Cari: No. Indikator / Label / Unit..." id="table-standar-search">
                                <button class="btn btn-outline-secondary" type="button" id="table-standar-clear-search" style="display: none;">
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
                    <div id="table-standar-active-filters" class="mt-2"></div>
                </form>

                {{-- DataTable --}}
                <x-tabler.datatable
                    id="table-standar"
                    route="{{ route('pemutu.indikator-summary.data-standar') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator & Unit', 'width' => '30%'],
                        ['data' => 'parent_info', 'name' => 'parent_no_indikator', 'title' => 'Parent', 'width' => '8%'],
                        ['data' => 'labels', 'name' => 'label_details', 'title' => 'Label', 'width' => '10%'],
                        ['data' => 'ed_detail', 'name' => 'ed_capaian_detail', 'title' => 'ED - Capaian', 'width' => '15%'],
                        ['data' => 'ed_analisis', 'name' => 'ed_analisis_detail', 'title' => 'ED - Analisis', 'width' => '15%'],
                        ['data' => 'ami_detail', 'name' => 'ami_hasil_detail', 'title' => 'AMI - Hasil', 'width' => '15%'],
                        ['data' => 'pengend_detail', 'name' => 'pengend_status_detail', 'title' => 'Pengendalian', 'width' => '12%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
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
    console.log('Standar page loaded');
    
    function exportExcel() {
        const params = new URLSearchParams();
        const form = document.getElementById('table-standar-filter');
        if (form) {
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
        }
        window.location.href = '{{ route('pemutu.indikator-summary.export') }}?' + params.toString();
    }
</script>
@endpush
@endsection
