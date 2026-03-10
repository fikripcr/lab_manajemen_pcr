@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('content')
    <x-tabler.card>
        <x-tabler.card-header>
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                <h3 class="card-title mb-0">List Indikator Standar</h3>
                <div class="ms-auto d-flex flex-wrap gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-standar" />
                    <x-tabler.datatable-search dataTableId="table-standar" />
                    <x-tabler.datatable-filter dataTableId="table-standar">
                        <div class="row g-2">
                            <div class="col-12">
                                <x-tabler.form-select name="kelompok_indikator" label="Kelompok" class="mb-2">
                                    <option value="">Semua Kelompok</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non Akademik">Non Akademik</option>
                                </x-tabler.form-select>
                            </div>
                            <div class="col-12">
                                <x-tabler.form-select name="year" label="Tahun Periode" class="mb-2">
                                    <option value="">Semua Tahun</option>
                                    @foreach($periodes as $periode)
                                        <option value="{{ $periode->tahun }}">{{ $periode->periode }}</option>
                                    @endforeach
                                </x-tabler.form-select>
                            </div>
                            <div class="col-12">
                                <x-tabler.form-select name="ed_status" label="Status ED (Evaluasi Diri)" class="mb-2">
                                    <option value="">Semua Status</option>
                                    <option value="filled">Sudah Isi</option>
                                    <option value="empty">Belum Isi</option>
                                </x-tabler.form-select>
                            </div>
                            <div class="col-12">
                                <x-tabler.form-select name="ami_hasil" label="Hasil AMI" class="mb-2">
                                    <option value="">Semua Hasil</option>
                                    <option value="empty">Belum Dinilai</option>
                                    <option value="0">KTS (Kesesuaian Tidak Terpenuhi)</option>
                                    <option value="1">Terpenuhi</option>
                                    <option value="2">Terlampaui</option>
                                </x-tabler.form-select>
                            </div>
                            <div class="col-12">
                                <x-tabler.form-select name="pengend_status" label="Status Pengendalian" class="mb-0">
                                    <option value="">Semua Status</option>
                                    <option value="filled">Sudah Isi</option>
                                    <option value="empty">Belum Isi</option>
                                </x-tabler.form-select>
                            </div>
                        </div>
                    </x-tabler.datatable-filter>
                    <x-tabler.button type="button" class="btn-success" onclick="exportExcel()" icon="ti ti-file-export" text="Export" />
                </div>
            </div>
        </x-tabler.card-header>
        <x-tabler.card-body class="p-0">
            <div class="row p-3">
                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-light-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader" title="Total penugasan indikator ke unit" data-bs-toggle="tooltip">Total Indikator Standar Unit</div>
                            </div>
                            <div class="h1 mb-0" id="count-edTotalUnits">{{ number_format($edTotalUnits) }}</div>
                            <div class="text-muted small">
                                (dari total <b id="count-uniqueAssignedStandar">{{ number_format($uniqueAssignedStandar) }}</b> unik)
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
                            <div class="h1 mb-3 text-success" id="count-edFilledUnits">{{ number_format($edFilledUnits) }}</div>
                            <div class="progress progress-sm">
                                @php $edProgress = $edTotalUnits > 0 ? round(($edFilledUnits / $edTotalUnits) * 100) : 0; @endphp
                                <div class="progress-bar bg-success" id="progress-ed" style="width: {{ $edProgress }}%"></div>
                            </div>
                            <div class="text-muted small"><span id="count-edProgress">{{ $edProgress }}</span>% dari <span id="count-edTotalUnits2">{{ $edTotalUnits }}</span> unit</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="bg-primary-lt border-0 shadow-none">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">Pelaksanaan AMI</div>
                            </div>
                            <div class="h1 mb-3 text-primary" id="count-amiAssessed">{{ number_format($amiAssessed) }}</div>
                            <div class="d-flex gap-2">
                                <span class="status status-danger" id="count-amiKts">{{ $amiKts }} KTS</span>
                                <span class="status status-success" id="count-amiTerpenuhi">{{ $amiTerpenuhi }} Terpenuhi</span>
                                <span class="status status-info" id="count-amiTerlampaui">{{ $amiTerlampaui }} Terlampaui</span>
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
                            <div class="h1 mb-3 text-info" id="count-pengendFilled">{{ number_format($pengendFilled) }}</div>
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
                    ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator & Unit', 'width' => '15%'],
                    ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '7%'],
                    ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Status ED', 'width' => '10%', 'class' => 'text-center'],
                    ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Status AMI', 'width' => '10%', 'class' => 'text-center'],
                    ['data' => 'rtp', 'name' => 'rtp', 'title' => 'RTP', 'width' => '8%', 'class' => 'text-center'],
                    ['data' => 'ptp', 'name' => 'ptp', 'title' => 'PTP', 'width' => '8%', 'class' => 'text-center'],
                    ['data' => 'te', 'name' => 'te', 'title' => 'TE', 'width' => '8%', 'class' => 'text-center'],
                    ['data' => 'pengend_detail', 'name' => 'v.pengend_status', 'title' => 'Pengendalian', 'width' => '10%', 'class' => 'text-center'],
                    ['data' => 'peningkatan_detail', 'name' => 'peningkatan_detail', 'title' => 'Peningkatan', 'width' => '10%', 'class' => 'text-center'],
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
<style>
    .summary-text-full { white-space: pre-wrap; word-break: break-word; }
    .summary-text-excerpt { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 250px; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Standar page ready');

        // Custom filter handling for summary cards
        const filterForm = document.getElementById('table-standar-filter');
        const searchInput = document.querySelector('[data-table-id="table-standar"][name="search"]');

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

            axios.get('{{ route('pemutu.indikator-summary.summary-count') }}?' + params.toString())
                .then(response => {
                    if (response.data.success) {
                        const d = response.data.data;
                        document.getElementById('count-edTotalUnits').textContent = d.edTotalUnits.toLocaleString();
                        document.getElementById('count-edTotalUnits2').textContent = d.edTotalUnits.toLocaleString();
                        document.getElementById('count-uniqueAssignedStandar').textContent = d.uniqueAssignedStandar.toLocaleString();
                        document.getElementById('count-edFilledUnits').textContent = d.edFilledUnits.toLocaleString();
                        document.getElementById('count-amiAssessed').textContent = d.amiAssessed.toLocaleString();
                        document.getElementById('count-amiKts').textContent = d.amiKts + ' KTS';
                        document.getElementById('count-amiTerpenuhi').textContent = d.amiTerpenuhi + ' Terpenuhi';
                        document.getElementById('count-amiTerlampaui').textContent = d.amiTerlampaui + ' Terlampaui';
                        document.getElementById('count-pengendFilled').textContent = d.pengendFilled.toLocaleString();

                        const progress = d.edTotalUnits > 0 ? Math.round((d.edFilledUnits / d.edTotalUnits) * 100) : 0;
                        document.getElementById('count-edProgress').textContent = progress;
                        document.getElementById('progress-ed').style.width = progress + '%';
                    }
                })
                .catch(error => console.error('Error refreshing summary', error));
        }

        // Listen for filter changes
        if (filterForm) {
            filterForm.addEventListener('change', refreshSummary);
        }
        
        // Listen for search changes (debounced)
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(refreshSummary, 500);
            });
        }

        // Also refresh when DataTable is re-drawn (e.g. initial load)
        $('#table-standar').on('draw.dt', function() {
            // Only refresh if search or filters might have changed
            // refreshSummary(); // Enabling this might cause double calls, but ensures consistency
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
    });

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
