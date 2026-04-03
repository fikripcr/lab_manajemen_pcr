@extends('layouts.tabler.app')
@section('title', 'Summary Indikator Standar - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Summary Indikator Standar {{ $siklus['tahun'] }}" pretitle="Summary">
    <x-slot:actions>
        <div class="btn-group p-1 bg-light rounded-pill shadow-sm" style="border: 1px solid #e6e8e9;">
            <a href="{{ route('pemutu.set-kelompok', 'akademik') }}" 
               class="btn {{ $activeKelompok === 'akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-school me-2"></i>Akademik
            </a>
            <a href="{{ route('pemutu.set-kelompok', 'non_akademik') }}" 
               class="btn {{ $activeKelompok === 'non_akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-building-community me-2"></i>Non Akademik
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @php 
        $kelompok = $activeKelompok === 'akademik' ? 'Akademik' : 'Non Akademik';
    @endphp
    
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                <div class="ms-auto d-flex flex-wrap gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-standar" />
                    <x-tabler.datatable-search dataTableId="table-standar" />
                    <x-tabler.datatable-filter dataTableId="table-standar" type="button" target="#table-standar-filter-area" />
                    <x-tabler.button type="button" class="btn-success" onclick="exportExcel('{{ $kelompok }}')" icon="ti ti-file-export" text="Export" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <div class="collapse" id="table-standar-filter-area">
                <x-tabler.datatable-filter dataTableId="table-standar" type="bare">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-tabler.form-select name="ed_status" id="ed_status" label="Status Evaluasi" class="mb-2" placeholder="">
                                <option value="all">Semua Status</option>
                                <option value="filled">Sudah Isi</option>
                                <option value="empty">Belum Isi</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-select name="ami_hasil" id="ami_hasil" label="Hasil AMI" class="mb-2" placeholder="">
                                <option value="all">Semua Hasil</option>
                                <option value="empty">Belum Dinilai</option>
                                <option value="0">KTS</option>
                                <option value="1">Terpenuhi</option>
                                <option value="2">Terlampaui</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-select name="pengend_status" id="pengend_status" label="Status Pengendalian" class="mb-0" placeholder="">
                                <option value="all">Semua Status</option>
                                <option value="filled">Sudah Isi</option>
                                <option value="empty">Belum Isi</option>
                            </x-tabler.form-select>
                        </div>
                    </div>
                </x-tabler.datatable-filter>
            </div>
            <div class="row p-3">
                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-light-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader" title="Total penugasan indikator ke unit" data-bs-toggle="tooltip">Total Indikator Standar Unit</div>
                            </div>
                            <div class="h1 mb-0" id="count-edTotalUnits">0</div>
                            <div class="text-muted small">
                                (dari total <b id="count-uniqueAssignedStandar">0</b> unik)
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-success-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Isi Evaluasi Diri</div>
                            </div>
                            <div class="h1 mb-3 text-success" id="count-edFilledUnits">0</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" id="progress-ed" style="width: 0%"></div>
                            </div>
                            <div class="text-muted small"><span id="count-edProgress">0</span>% dari <span id="count-edTotalUnits2">0</span> unit</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-primary-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Pelaksanaan AMI</div>
                            </div>
                            <div class="h1 mb-3 text-primary" id="count-amiAssessed">0</div>
                            <div class="d-flex gap-2">
                                <span class="status status-danger" title="KTS"><i class="ti ti-alert-triangle"></i><span id="count-amiKts">0</span></span>
                                <span class="status status-success" title="Terpenuhi"><i class="ti ti-check"></i><span id="count-amiTerpenuhi">0</span></span>
                                <span class="status status-info" title="Terlampaui"><i class="ti ti-rocket"></i><span id="count-amiTerlampaui">0</span></span>
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-info-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Pengendalian</div>
                            </div>
                            <div class="h1 mb-3 text-info" id="count-pengendFilled">0</div>
                            <div class="text-muted small">Unit dengan pengendalian aktif</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>

            <x-tabler.datatable
                id="table-standar"
                route="{{ route('pemutu.indikator-summary.data-standar') }}"
                :columns="[
                    ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator & Unit', 'width' => '15%', 'orderable' => false, 'searchable' => false],
                    ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '7%', 'orderable' => false, 'searchable' => false],
                    ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Evaluasi Diri', 'width' => '15%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Status AMI', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'temuan_sebab_akibat', 'name' => 'io.ami_hasil_temuan', 'title' => 'Temuan, Sebab & Akibat', 'width' => '20%', 'searchable' => false],
                    ['data' => 'auditor_recom', 'name' => 'io.ami_hasil_temuan_rekom', 'title' => 'Rekomendasi Auditor', 'width' => '15%', 'searchable' => false],
                    ['data' => 'rtp', 'name' => 'rtp', 'title' => 'RTP', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'ptp', 'name' => 'ptp', 'title' => 'PTP', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'te', 'name' => 'te', 'title' => 'TE', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'pengend_detail', 'name' => 'io.pengend_status', 'title' => 'Pengendalian', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ['data' => 'peningkatan_detail', 'name' => 'peningkatan_detail', 'title' => 'Peningkatan', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
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
@endsection

@push('scripts')
<style>
    .summary-text-full { white-space: pre-wrap; word-break: break-word; }
    .summary-text-excerpt { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 250px; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function refreshSummary() {
            const filterForm = document.getElementById('table-standar-filter');
            const searchInput = document.querySelector('[data-table-id="table-standar"][name="search"]');

            const params = new URLSearchParams();
            if (filterForm) {
                const formData = new FormData(filterForm);
                for (const [key, value] of formData.entries()) {
                    if (value && value !== 'all') params.append(key, value);
                }
            }
            if (searchInput && searchInput.value) {
                params.append('search[value]', searchInput.value);
            }

            axios.get('{{ route('pemutu.indikator-summary.summary-count') }}?' + params.toString())
                .then(response => {
                    if (response.data.success) {
                        const d = response.data.data;
                        document.getElementById('count-edTotalUnits').textContent = Number(d.edTotalUnits).toLocaleString();
                        document.getElementById('count-edTotalUnits2').textContent = Number(d.edTotalUnits).toLocaleString();
                        document.getElementById('count-uniqueAssignedStandar').textContent = Number(d.uniqueAssignedStandar).toLocaleString();
                        document.getElementById('count-edFilledUnits').textContent = Number(d.edFilledUnits).toLocaleString();
                        document.getElementById('count-amiAssessed').textContent = Number(d.amiAssessed).toLocaleString();
                        document.getElementById('count-amiKts').textContent = Number(d.amiKts).toLocaleString();
                        document.getElementById('count-amiTerpenuhi').textContent = Number(d.amiTerpenuhi).toLocaleString();
                        document.getElementById('count-amiTerlampaui').textContent = Number(d.amiTerlampaui).toLocaleString();
                        document.getElementById('count-pengendFilled').textContent = Number(d.pengendFilled).toLocaleString();

                        const progress = d.edTotalUnits > 0 ? Math.round((d.edFilledUnits / d.edTotalUnits) * 100) : 0;
                        document.getElementById('count-edProgress').textContent = progress;
                        document.getElementById('progress-ed').style.width = progress + '%';
                    }
                })
                .catch(error => console.error('Error refreshing summary', error));
        }

        // Initial load
        refreshSummary();

        const filterForm = document.getElementById('table-standar-filter');
        if (filterForm) {
            filterForm.addEventListener('change', () => refreshSummary());
        }

        const searchInput = document.querySelector('[data-table-id="table-standar"][name="search"]');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => refreshSummary(), 500);
            });
        }
    });

    // Use delegation for read more buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-read-more')) {
            const targetId = e.target.getAttribute('data-target');
            const excerpt = document.getElementById(targetId + '-excerpt');
            const full = document.getElementById(targetId + '-full');

            if (excerpt && full) {
                if (full.classList.contains('d-none')) {
                    full.classList.remove('d-none');
                    excerpt.classList.add('d-none');
                    e.target.textContent = 'Sembunyikan';
                } else {
                    full.classList.add('d-none');
                    excerpt.classList.remove('d-none');
                    e.target.textContent = 'Selengkapnya';
                }
            }
        }
    });

    function exportExcel(kelompok) {
        const params = new URLSearchParams();
        params.append('tahun', '{{ $siklus["tahun"] }}');
        params.append('kelompok_indikator', kelompok);

        const form = document.getElementById('table-standar-filter');
        if (form) {
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value && value !== 'all') params.append(key, value);
            }
        }
        window.location.href = '{{ route('pemutu.indikator-summary.export') }}?' + params.toString();
    }
</script>
@endpush
