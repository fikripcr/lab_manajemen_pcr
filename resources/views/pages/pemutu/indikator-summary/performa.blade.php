@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                <h3 class="card-title mb-0">List Indikator Performa (KPI)</h3>
                <div class="d-flex flex-wrap gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-performa" />
                    <x-tabler.datatable-search dataTableId="table-performa" />
                    
                    <x-tabler.datatable-filter dataTableId="table-performa" type="button" target="#table-performa-filter-area" />
                    <x-tabler.button type="button" class="btn-success" onclick="exportExcel()" icon="ti ti-file-export" text="Export" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <div class="collapse" id="table-performa-filter-area">
                <x-tabler.datatable-filter dataTableId="table-performa" type="bare">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-tabler.form-select name="kelompok_indikator" label="Kelompok" placeholder="">
                                <option value="all">Semua Kelompok</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non Akademik">Non Akademik</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-select name="pegawai_id" id="performa-filter-pegawai" label="Pegawai" placeholder="">
                                <option value="all">Semua Pegawai</option>
                                @foreach($pegawais as $pegawai)
                                    <option value="{{ $pegawai->pegawai_id }}">{{ $pegawai->nama }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-select name="unit_id" id="performa-filter-unit" label="Unit" placeholder="">
                                <option value="all">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </div>
                </x-tabler.datatable-filter>
            </div>
            {{-- Summary Cards --}}
            <div class="row p-3">
                <div class="col-sm-6 col-lg-4">
                    <x-tabler.card class="bg-light-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Indikator Performa</div>
                            </div>
                            <div class="h1 mb-0" id="count-performaTotal">{{ number_format($totalIndikatorActive) }}</div>
                            <div class="text-muted small">Indikator performa untuk KPI Pegawai</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <x-tabler.card class="bg-success-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total KPI Aktif</div>
                            </div>
                            <div class="h1 mb-0 text-success" id="count-kpiTotalPegawai">{{ number_format($kpiTotalPegawai ?? 0) }}</div>
                            <div class="text-muted small">Pegawai dengan KPI aktif</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <x-tabler.card class="bg-info-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Avg Score KPI</div>
                            </div>
                            <div class="h1 mb-0 text-info" id="count-kpiAvgScore">{{ number_format($kpiAvgScore ?? 0, 1) }}</div>
                            <div class="text-muted small">Rata-rata skor KPI semua pegawai</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>

            <x-tabler.datatable
                id="table-performa"
                route="{{ route('pemutu.indikator-summary.data-performa') }}"
                :columns="[
                    ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator Performa', 'width' => '20%'],
                    ['data' => 'target', 'name' => 'target_value', 'title' => 'Target', 'width' => '5%'],
                    ['data' => 'capaian', 'name' => 'realization', 'title' => 'Capaian', 'width' => '10%'],
                    ['data' => 'analisis', 'name' => 'kpi_analisis', 'title' => 'Analisis', 'width' => '20%'],
                    ['data' => 'labels', 'name' => 'labels', 'title' => 'Label', 'width' => '10%'],
                    ['data' => 'kpi_detail', 'name' => 'pegawai.nama', 'title' => 'Pegawai (Unit)', 'width' => '15%'],
                    ['data' => 'kpi_score', 'name' => 'score', 'title' => 'Score', 'width' => '10%', 'class' => 'text-center'],
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
        </x-tabler.card-body>
    </x-tabler.card>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Performa page ready');

        const filterForm = document.getElementById('table-performa-filter');
        const searchInput = document.querySelector('[data-table-id="table-performa"][name="search"]');

        function refreshSummary() {
            const params = new URLSearchParams();
            if (filterForm) {
                const formData = new FormData(filterForm);
                for (const [key, value] of formData.entries()) {
                    if (value) params.append(key, value);
                }
            }
            if (searchInput && searchInput.value) {
                params.append('search[value]', searchInput.value);
            }

            axios.get('{{ route('pemutu.indikator-summary.performa.summary-count') }}?' + params.toString())
                .then(response => {
                    if (response.data.success) {
                        const d = response.data.data;
                        document.getElementById('count-performaTotal').textContent = d.totalIndikatorActive.toLocaleString();
                        document.getElementById('count-kpiTotalPegawai').textContent = d.kpiTotalPegawai.toLocaleString();
                        document.getElementById('count-kpiAvgScore').textContent = d.kpiAvgScore.toFixed(1);
                    }
                })
                .catch(error => console.error('Error refreshing summary', error));
        }

        if (filterForm) {
            filterForm.addEventListener('change', refreshSummary);
        }

        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(refreshSummary, 500);
            });
        }
    });

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
