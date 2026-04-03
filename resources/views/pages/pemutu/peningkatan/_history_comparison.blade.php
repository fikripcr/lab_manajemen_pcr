@php
    $curr = $comparison['current'];
    $prev = $comparison['prev'];
    $isIndikatorChanged = $prev && $curr['text'] !== $prev['text'];
    $isTargetChanged = $prev && $curr['target'] !== $prev['target'];
@endphp

<div class="modal-body p-4 bg-light-lt">
    <!-- Context Header -->
    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
        <div class="card-status-top bg-info"></div>
        <div class="card-body p-0">
            <div class="d-flex align-items-stretch">
                <div class="bg-info-lt p-4 d-flex align-items-center border-end">
                    <div class="avatar avatar-md bg-info text-white shadow-sm">
                        <i class="ti ti-building-community"></i>
                    </div>
                </div>
                <div class="p-4 flex-fill">
                    <h3 class="mb-1 text-uppercase tracking-wide">{{ $curr['unit_name'] }}</h3>
                    <div class="text-muted d-flex align-items-center gap-2">
                        <span class="badge bg-secondary-lt">{{ $curr['unit_code'] }}</span>
                        <span class="text-muted small">•</span>
                        <span class="small fw-medium">Konteks Unit Kerja</span>
                    </div>
                </div>
                @if($prev)
                <div class="ms-auto p-4 border-start bg-light d-flex flex-column justify-content-center text-center px-5">
                    <span class="text-muted small d-block mb-1">Status AMI Lalu</span>
                    <span class="status status-{{ $prev['status'] === 'sesuai' ? 'success' : ($prev['status'] === 'penyesuaian' ? 'warning' : 'danger') }} fs-3 py-1 px-3">
                        {{ ucwords($prev['status'] ?? 'N/A') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($prev && $prev['analisis_atsn'])
    <div class="alert alert-info border-0 shadow-sm bg-info-lt mb-4">
        <div class="d-flex gap-3">
            <i class="ti ti-message-dots fs-3 mt-1"></i>
            <div>
                <div class="fw-bold fs-3 mb-1">Catatan Evaluasi AMI Periode Sebelumnya</div>
                <div class="fst-italic text-secondary">{{ $prev['analisis_atsn'] }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- NAMA INDIKATOR -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-bottom py-3 bg-white">
                    <h4 class="card-title text-secondary">
                        <i class="ti ti-align-left me-2"></i>Nama Indikator
                    </h4>
                </div>
                <div class="card-body">
                    @if($isIndikatorChanged)
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-2">SEBELUMNYA:</span>
                            <div class="p-3 bg-gray-50 border rounded text-secondary fst-italic">
                                {{ $prev['text'] }}
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <i class="ti ti-arrow-down fs-2 text-primary"></i>
                        </div>
                        <div>
                            <span class="badge bg-primary-lt mb-2">SESUDAH (DRAFT):</span>
                            <div class="p-3 border border-primary bg-primary-lt rounded fw-bold text-primary shadow-sm">
                                {{ $curr['text'] }}
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-white border rounded shadow-sm">
                            {{ $curr['text'] }}
                            <div class="mt-2 small text-success d-flex align-items-center">
                                <i class="ti ti-check me-1"></i> Tidak Berubah (Tetap)
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TARGET -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-bottom py-3 bg-white">
                    <h4 class="card-title text-secondary">
                        <i class="ti ti-target me-2"></i>Target Unit
                    </h4>
                </div>
                <div class="card-body">
                    @if($isTargetChanged)
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-2">TARGET LAMA:</span>
                            <div class="p-3 bg-gray-50 border rounded text-secondary h2 mb-0">
                                {{ $prev['target'] ?? '-' }}
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <i class="ti ti-arrow-right fs-1 text-primary"></i>
                        </div>
                        <div>
                            <span class="badge bg-yellow-lt mb-2 text-warning">TARGET BARU (DRAFT):</span>
                            <div class="p-3 border border-yellow bg-yellow-lt rounded h1 mb-0 text-dark shadow-sm">
                                {{ $curr['target'] ?? '-' }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between p-4 bg-white border rounded shadow-sm h-100">
                            <div>
                                <span class="text-muted d-block small mb-1">Target Hiện tại</span>
                                <div class="h2 mb-0">{{ $curr['target'] ?? '-' }}</div>
                            </div>
                            <div class="text-success text-center">
                                <i class="ti ti-circle-check fs-1"></i>
                                <div class="small fw-bold">Target Tetap</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-white border-top-0 d-flex justify-content-between p-3">
    <div class="text-muted small">
        <i class="ti ti-info-circle me-1"></i>
        Riwayat ini ditampilkan berdasarkan keterkaitan indikator antar periode.
    </div>
    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
