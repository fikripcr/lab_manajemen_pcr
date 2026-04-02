@extends('layouts.tabler.app')
@section('title', 'Histori PPEPP 5 Tahun')

@section('header')
<x-tabler.page-header 
    title="📊 Histori PPEPP 5 Tahun" 
    pretitle="Penjaminan Mutu Internal"
    subtitle="Progres indikator dari tahun {{ $minYear }} hingga {{ $currentYear }}"
>
    <x-slot:actions>
        <div class="d-flex align-items-center gap-3">
            <div class="btn-group p-1 bg-light rounded-pill shadow-sm" style="border: 1px solid #e6e8e9;">
                <a href="{{ route('pemutu.set-kelompok', 'akademik') }}" 
                   class="btn {{ $kelompok === 'akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                    <i class="ti ti-school me-2"></i>Akademik
                </a>
                <a href="{{ route('pemutu.set-kelompok', 'non_akademik') }}" 
                   class="btn {{ $kelompok === 'non_akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                    <i class="ti ti-building-community me-2"></i>Non Akademik
                </a>
            </div>

            <a href="{{ route('pemutu.indikator-summary.standar') }}" class="btn btn-ghost-secondary">
                <i class="ti ti-list me-2"></i>Summary Indikator
            </a>
            <a href="{{ route('pemutu.dashboard') }}" class="btn btn-ghost-secondary">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
{{-- Info Banner --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary border-0 shadow-sm">
            <div class="card-body text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="ti ti-info-circle fs-1 me-3 opacity-75"></i>
                    <div>
                        <h5 class="mb-1">Dashboard Historis PPEPP</h5>
                        <p class="mb-0 opacity-75 small">
                            Memantau perjalanan indikator melalui siklus PPEPP 
                            (Penetapan → Pelaksanaan → Evaluasi → Pengendalian → Peningkatan) 
                            dalam 5 tahun terakhir
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar avatar-lg rounded">
                            <i class="ti ti-target fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-muted fw-bold small">Total Indikator</div>
                        <div class="h3 mb-0 fw-bold text-primary">{{ number_format($stats['total_indicators'] ?? 0) }}</div>
                        <div class="text-muted small">{{ $minYear }} - {{ $currentYear }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-success text-white avatar avatar-lg rounded">
                            <i class="ti ti-check fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-muted fw-bold small">AMI Terpenuhi</div>
                        <div class="h3 mb-0 fw-bold text-success">{{ number_format($stats['ami_stats']['terpenuhi'] ?? 0) }}</div>
                        <div class="text-muted small">Tahun {{ $currentYear }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-info text-white avatar avatar-lg rounded">
                            <i class="ti ti-rocket fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-muted fw-bold small">AMI Terlampaui</div>
                        <div class="h3 mb-0 fw-bold text-info">{{ number_format($stats['ami_stats']['terlampaui'] ?? 0) }}</div>
                        <div class="text-muted small">Tahun {{ $currentYear }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-danger text-white avatar avatar-lg rounded">
                            <i class="ti ti-alert-triangle fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-muted fw-bold small">AMI KTS</div>
                        <div class="h3 mb-0 fw-bold text-danger">{{ number_format($stats['ami_stats']['kts'] ?? 0) }}</div>
                        <div class="text-muted small">Tahun {{ $currentYear }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Main Content: Timeline View --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="ti ti-timeline me-2 text-primary"></i>
                        Timeline Indikator per Tahun
                    </h3>
                    <span class="badge bg-primary-lt">
                        {{ count($summaryData['indicators']) }} Indikator
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="timelineTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th style="width: 30%">Indikator</th>
                                <th class="text-center" style="width: 10%">Target</th>
                                @for ($year = $currentYear; $year >= $minYear; $year--)
                                    <th class="text-center" style="width: 11%">
                                        <div class="fw-bold">{{ $year }}</div>
                                        <small class="text-muted">Status AMI</small>
                                    </th>
                                @endfor
                                <th class="text-center" style="width: 12%">
                                    <i class="ti ti-trending-up me-1"></i>Trend
                                </th>
                                <th class="text-center" style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($summaryData['indicators'] as $rootId => $group)
                                @php
                                    $root = $group['root'];
                                    $years = $group['years'];
                                    $trend = $group['trend'] ?? 'Data tidak cukup';
                                @endphp
                                <tr class="indicator-row" style="cursor: pointer;" onclick="showDetail({{ $rootId }})">
                                    <td class="text-center text-muted">{{ $no++ }}</td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1">
                                            {{ Str::limit($root->indikator, 100) }}
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-secondary-lt">
                                                <i class="ti ti-code me-1"></i>{{ $root->no_indikator }}
                                            </span>
                                            @if($root->kelompok_indikator)
                                                <span class="badge bg-azure-lt">
                                                    <i class="ti ti-tag me-1"></i>{{ $root->kelompok_indikator }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-lt fw-bold">
                                            {{ $root->target }} 
                                            <small class="ms-1">{{ $root->unit_ukuran }}</small>
                                        </span>
                                    </td>
                                    @for ($year = $currentYear; $year >= $minYear; $year--)
                                        <td class="text-center">
                                            @if (isset($group['chain'][$year]))
                                                @php
                                                    $yearData = $group['chain'][$year];
                                                    $amiStatus = $yearData->ami_hasil_akhir ?? null;
                                                    
                                                    $amiBadge = match($amiStatus) {
                                                        0 => ['icon' => 'ti-x', 'text' => 'KTS', 'color' => 'danger', 'title' => 'Kurang Dari Standar'],
                                                        1 => ['icon' => 'ti-check', 'text' => 'Terpenuhi', 'color' => 'success', 'title' => 'Standar Terpenuhi'],
                                                        2 => ['icon' => 'ti-rocket', 'text' => 'Terlampaui', 'color' => 'info', 'title' => 'Standar Terlampaui'],
                                                        default => ['icon' => 'ti-minus', 'text' => '-', 'color' => 'secondary', 'title' => 'Belum Dinilai'],
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $amiBadge['color'] }}-lt" 
                                                      title="{{ $amiBadge['title'] }}"
                                                      data-bs-toggle="tooltip">
                                                    <i class="ti {{ $amiBadge['icon'] }} me-1"></i>
                                                    {{ $amiBadge['text'] }}
                                                </span>
                                            @else
                                                <span class="text-muted" title="Tidak ada data" data-bs-toggle="tooltip">
                                                    <i class="ti ti-dash"></i>
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="text-center">
                                        @php
                                            $trendConfig = match($trend) {
                                                'Meningkat' => ['icon' => 'ti-arrow-up', 'color' => 'success', 'label' => 'Meningkat'],
                                                'Menurun' => ['icon' => 'ti-arrow-down', 'color' => 'danger', 'label' => 'Menurun'],
                                                'Stabil' => ['icon' => 'ti-arrows-horizontal', 'color' => 'warning', 'label' => 'Stabil'],
                                                default => ['icon' => 'ti-minus', 'color' => 'secondary', 'label' => 'N/A'],
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $trendConfig['color'] }}-lt">
                                            <i class="ti {{ $trendConfig['icon'] }} me-1"></i>
                                            {{ $trendConfig['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-ghost-primary" 
                                                onclick="event.stopPropagation(); showDetail({{ $rootId }})"
                                                title="Lihat Detail PPEPP"
                                                data-bs-toggle="tooltip">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 7 + ($currentYear - $minYear) }}" class="text-center py-5">
                                        <i class="ti ti-database-off text-muted fs-1 mb-3 d-block"></i>
                                        <p class="text-muted">Belum ada data indikator untuk periode ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
{{-- Detail Modal - Full Screen --}}
<div class="modal modal-blur fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ti ti-clock me-2"></i>
                    Detail Historis PPEPP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light" id="detailModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="text-muted">Memuat data historis...</h5>
                    <p class="text-muted small">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function showDetail(rootIndikatorId) {
        // Check if Bootstrap is loaded
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded!');
            alert('Error: Bootstrap belum dimuat. Silakan refresh halaman.');
            return;
        }
        
        const modalElement = document.getElementById('detailModal');
        const modalBody = document.getElementById('detailModalBody');
        
        // Remove any existing modal instance
        const existingModal = bootstrap.Modal.getInstance(modalElement);
        if (existingModal) {
            existingModal.dispose();
        }
        
        // Create new modal instance with proper options
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        modal.show();
        
        // Load detail via AJAX - use direct URL construction
        const detailUrl = '{{ url('pemutu/five-year-summary/detail') }}/' + rootIndikatorId;

        axios.get(detailUrl)
            .then(response => {
                if (response.data.success) {
                    renderDetail(response.data.data);
                } else {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="ti ti-alert-triangle me-2"></i>
                            Gagal memuat data
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading detail:', error);
                modalBody.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Terjadi kesalahan saat memuat data
                    </div>
                `;
            });
    }

    function renderDetail(data) {
        const modalBody = document.getElementById('detailModalBody');
        const timeline = data.timeline || [];
        
        if (timeline.length === 0) {
            modalBody.innerHTML = '<div class="alert alert-warning m-3">Tidak ada data historis</div>';
            return;
        }

        let html = '<div class="container-fluid p-4">';
        
        // Header Info
        const firstYear = timeline[0];
        html += `
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-primary text-white">
                    <h4 class="mb-2">
                        <i class="ti ti-target me-2"></i>
                        ${firstYear.no_indikator || 'N/A'}
                    </h4>
                    <p class="mb-0 opacity-75">${firstYear.indikator_text || '-'}</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-primary">
                            <i class="ti ti-ruler me-1"></i>Target: ${firstYear.target || '-'} ${firstYear.unit_ukuran || ''}
                        </span>
                    </div>
                </div>
            </div>
        `;

        // Timeline Cards per Year
        html += '<div class="row">';
        html += '<div class="col-12">';
        html += '<div class="timeline timeline-simple">';

        timeline.forEach((yearData, index) => {
            const isLatest = index === 0;
            const cardColor = isLatest ? 'primary' : 'secondary';
            
            html += `
                <div class="timeline-item">
                    <div class="timeline-marker ${isLatest ? 'bg-primary' : 'bg-secondary'}"></div>
                    <div class="timeline-content">
                        <div class="card border-0 shadow-sm ${isLatest ? 'border-primary' : ''}">
                            <div class="card-header bg-${cardColor}-lt d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0 text-${cardColor}">
                                    <i class="ti ti-calendar me-2"></i>Tahun ${yearData.tahun}
                                </h4>
                                ${yearData.origin_from ? `
                                    <span class="badge bg-${cardColor}">
                                        <i class="ti ti-info-circle me-1"></i>${yearData.origin_from}
                                    </span>
                                ` : ''}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Penetapan -->
                                    <div class="col-md-6">
                                        <div class="card bg-light-lt border-0 h-100">
                                            <div class="card-body">
                                                <h6 class="subheader mb-3">
                                                    <i class="ti ti-target text-primary me-2"></i>
                                                    1. Penetapan
                                                </h6>
                                                <div class="small">
                                                    <p class="mb-2"><strong>Indikator:</strong></p>
                                                    <p class="text-muted mb-3">${yearData.indikator_text || '-'}</p>
                                                    <p class="mb-2"><strong>Target:</strong></p>
                                                    <p class="text-muted">${yearData.target || '-'} ${yearData.unit_ukuran || ''}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Evaluasi -->
                                    <div class="col-md-6">
                                        <div class="card bg-light-lt border-0 h-100">
                                            <div class="card-body">
                                                <h6 class="subheader mb-3">
                                                    <i class="ti ti-clipboard-check text-success me-2"></i>
                                                    2. Evaluasi (ED & AMI)
                                                </h6>
                                                ${renderOrgUnits(yearData.org_units)}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PPEPP Flow Visualization -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card bg-light-lt border-0">
                                            <div class="card-body">
                                                <h6 class="subheader mb-3">
                                                    <i class="ti ti-flowchart text-info me-2"></i>
                                                    Status Siklus PPEPP
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    ${renderPpeppStatus(yearData)}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>'; // End timeline
        html += '</div>'; // End col
        html += '</div>'; // End row
        html += '</div>'; // End container

        modalBody.innerHTML = html;
    }

    function renderOrgUnits(orgUnits) {
        if (!orgUnits || orgUnits.length === 0) {
            return '<p class="text-muted small">Belum ada data evaluasi dari unit</p>';
        }

        let html = '<div class="space-y-2">';
        
        orgUnits.forEach((unit, idx) => {
            const amiLabels = {
                0: {text: 'KTS', color: 'danger', icon: 'ti-x'},
                1: {text: 'Terpenuhi', color: 'success', icon: 'ti-check'},
                2: {text: 'Terlampaui', color: 'info', icon: 'ti-rocket'}
            };
            const amiLabel = amiLabels[unit.ami_hasil_akhir] || {text: 'Belum Dinilai', color: 'secondary', icon: 'ti-minus'};

            html += `
                <div class="card border-0 bg-white">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="fw-bold small">
                                <i class="ti ti-building me-1"></i>
                                ${unit.unit_name || 'Unit ' + (idx + 1)}
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                ${unit.ed_capaian ? `
                                    <span class="badge bg-success-lt">
                                        <i class="ti ti-edit me-1"></i>ED Terisi
                                    </span>
                                ` : ''}
                                <span class="badge bg-${amiLabel.color}-lt">
                                    <i class="ti ${amiLabel.icon} me-1"></i>
                                    AMI: ${amiLabel.text}
                                </span>
                            </div>
                        </div>
                        ${unit.ami_hasil_temuan ? `
                            <div class="mt-2 small">
                                <strong class="text-danger"><i class="ti ti-alert-triangle me-1"></i>Temuan:</strong>
                                <p class="text-muted mb-0">${unit.ami_hasil_temuan}</p>
                            </div>
                        ` : ''}
                        ${unit.ami_rtp_isi ? `
                            <div class="mt-2 small">
                                <strong class="text-primary"><i class="ti ti-plan me-1"></i>RTP:</strong>
                                <p class="text-muted mb-0">${unit.ami_rtp_isi}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        html += '</div>';
        return html;
    }

    function renderPpeppStatus(yearData) {
        const hasED = yearData.org_units?.some(ou => ou.ed_capaian);
        const hasAMI = yearData.org_units?.some(ou => ou.ami_hasil_akhir !== null);
        const hasPengendalian = yearData.org_units?.some(ou => ou.pengend_status);
        const hasPeningkatan = yearData.has_next;

        const steps = [
            {
                key: 'penetapan',
                label: 'Penetapan',
                icon: 'ti-target',
                active: true, // Always active since we have the indicator
                color: 'primary'
            },
            {
                key: 'pelaksanaan',
                label: 'Pelaksanaan',
                icon: 'ti-play',
                active: true, // Assumed if there's data
                color: 'azure'
            },
            {
                key: 'evaluasi',
                label: 'Evaluasi',
                icon: 'ti-clipboard-check',
                active: hasED || hasAMI,
                color: 'success'
            },
            {
                key: 'pengendalian',
                label: 'Pengendalian',
                icon: 'ti-settings',
                active: hasPengendalian,
                color: 'warning'
            },
            {
                key: 'peningkatan',
                label: 'Peningkatan',
                icon: 'ti-trending-up',
                active: hasPeningkatan,
                color: 'info'
            }
        ];

        let html = '';
        steps.forEach((step, index) => {
            const isActive = step.active;
            const isLast = index === steps.length - 1;
            
            html += `
                <div class="text-center" style="min-width: 120px;">
                    <div class="avatar avatar-lg bg-${isActive ? step.color : 'secondary'}-lt text-${isActive ? step.color : 'secondary'} mb-2">
                        <i class="ti ${step.icon}"></i>
                    </div>
                    <div class="small fw-bold ${isActive ? 'text-' + step.color : 'text-muted'}">
                        ${step.label}
                    </div>
                    ${!isLast ? '<i class="ti ti-chevron-right text-muted mx-2"></i>' : ''}
                </div>
            `;
        });

        return html;
    }
</script>

<style>
    #timelineTable tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }
    
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-item {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }
    
    .timeline-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
        z-index: 1;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Gradient background for info banner */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #206bc4 0%, #4299e1 100%);
    }
</style>

<script>
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (typeof bootstrap !== 'undefined') {
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
    });
</script>
@endpush
