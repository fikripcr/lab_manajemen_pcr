@extends('layouts.tabler.app')
@section('title', 'Summary Indikator')

@section('content')
<div class="row">
    {{-- Summary Cards --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-standar" role="tab">
                            <i class="ti ti-book me-2"></i>Indikator Standar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-performa" role="tab">
                            <i class="ti ti-chart-bar me-2"></i>Indikator Performa (KPI)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Tab Indikator Standar --}}
                    <div class="tab-pane fade show active" id="tab-standar" role="tabpanel">
                        @include('pages.pemutu.indikator-summary._tab-standar', compact('periodes', 'totalIndikatorActive', 'totalIndikator', 'edTotalUnits', 'edFilledUnits', 'amiAssessed', 'amiKts', 'amiTerpenuhi', 'amiTerlampaui', 'pengendFilled'))
                    </div>

                    {{-- Tab Indikator Performa (KPI) --}}
                    <div class="tab-pane fade" id="tab-performa" role="tabpanel">
                        @include('pages.pemutu.indikator-summary._tab-performa')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportExcel(type = 'standar') {
        const params = new URLSearchParams();
        const form = document.getElementById('table-' + type + '-filter');
        if (form) {
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
        }
        params.append('type', type === 'standar' ? 'standar' : 'performa');
        window.location.href = '{{ route('pemutu.indikator-summary.export') }}?' + params.toString();
    }

    // Handle tab change - refresh DataTable when tab is shown
    document.addEventListener('shown.bs.tab', function (event) {
        const target = event.target.getAttribute('href');
        if (target === '#tab-performa') {
            // Refresh performa table when tab is shown
            if (window.DT_table-performa) {
                window.DT_table-performa.table.draw();
            }
        }
    });
</script>
@endpush
@endsection
