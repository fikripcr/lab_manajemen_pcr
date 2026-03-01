@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('content')
<div class="row">
    {{-- Summary Cards --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Summary Cards --}}
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader" title="Total penugasan indikator ke unit" data-bs-toggle="tooltip">Total Indikator Standar Unit</div>
                                </div>
                                <div class="h1 mb-0">{{ number_format($edTotalUnits) }}</div>
                                    <div class="text-muted small">
                                        <br>(dari total <b>{{ number_format($uniqueAssignedStandar) }} </b>indikator unik)
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Isi Evaluasi Diri</div>
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
                                    <div class="subheader">Pelaksanaan AMI</div>
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
                            <x-tabler.form-select name="kelompok_indikator" id="standar-filter-kelompok" class="form-select-sm">
                                <option value="">Semua Kelompok</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non Akademik">Non Akademik</option>
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-2">
                            <x-tabler.form-select name="year" id="standar-filter-year" class="form-select-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->tahun }}">{{ $periode->periode }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" placeholder="Cari: No. Indikator / Label / Unit..." id="table-standar-search">
                                <x-tabler.button type="button" class="btn-outline-secondary" id="table-standar-clear-search" style="display: none;" iconOnly="true" icon="ti ti-x" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <x-tabler.button type="button" class="btn-success btn-sm w-100" onclick="exportExcel()" icon="ti ti-file-export" text="Export" />
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
                        ['data' => 'indikator_full', 'name' => 'no_indikator', 'title' => 'Indikator & Unit', 'width' => '25%'],
                        ['data' => 'parent_info', 'name' => 'parent_no_indikator', 'title' => 'Parent', 'width' => '8%'],
                        ['data' => 'labels', 'name' => 'label_details', 'title' => 'Label', 'width' => '10%'],
                        ['data' => 'ed_detail', 'name' => 'ed_capaian', 'title' => 'Capaian', 'width' => '10%', 'class' => 'text-center'],
                        ['data' => 'ed_analisis', 'name' => 'ed_analisis', 'title' => 'Analisis', 'width' => '15%'],
                        ['data' => 'ami_detail', 'name' => 'ami_hasil_label', 'title' => 'AMI Hasil', 'width' => '15%', 'class' => 'text-left'],
                        ['data' => 'pengend_detail', 'name' => 'pengend_status', 'title' => 'Pengendalian', 'width' => '12%'],
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
<style>
    .summary-text-full { white-space: pre-wrap; word-break: break-word; }
    .summary-text-excerpt { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 250px; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Standar page ready');

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
