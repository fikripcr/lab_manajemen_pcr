<div class="mb-4">
    <h3 class="mb-1">{{ $title }}</h3>
    @if($jenis)
    <span class="badge bg-primary-lt text-uppercase">{{ $jenis }}</span>
    @endif
    <span class="text-muted ms-2"><i class="ti ti-target me-1"></i> {{ $indicators->count() }} Indikator Ditemukan</span>
</div>

<div class="row row-cards mb-4">
    <div class="col-sm-4">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-success text-white me-3">
                        <i class="ti ti-checks fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-success">{{ $percentages['terpenuhi'] }}% ({{ $amiCounts['terpenuhi'] }})</div>
                        <div class="text-muted small">Terpenuhi</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    <div class="col-sm-4">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-azure text-white me-3">
                        <i class="ti ti-rocket fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-azure">{{ $percentages['melampaui'] }}% ({{ $amiCounts['melampaui'] }})</div>
                        <div class="text-muted small">Melampaui</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    <div class="col-sm-4">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-danger text-white me-3">
                        <i class="ti ti-alert-triangle fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-danger">{{ $percentages['kts'] }}% ({{ $amiCounts['kts'] }})</div>
                        <div class="text-muted small">KTS (Tidak Terpenuhi)</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>

<h4 class="mb-3">Detail Capaian Unit ({{ $unitsEvaluated->count() }} Unit)</h4>

@if(empty($detailUnits))
<div class="text-center text-muted p-4 border rounded bg-light">
    <i class="ti ti-info-circle fs-2 mb-2 d-block"></i>
    Belum ada data evaluasi unit untuk struktur turunan ini.
</div>
@else
<div class="accordion" id="accordion-units">
    @foreach($detailUnits as $unitName => $data)
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading-{{ Str::slug($unitName) }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($unitName) }}" aria-expanded="false">
                <div class="w-100 d-flex justify-content-between align-items-center pe-3">
                    <span class="fw-bold">{{ $unitName }}</span>
                    <div class="d-flex gap-2 text-center" style="font-size: 0.8rem;">
                        <span class="badge bg-success-lt" title="Terpenuhi">{{ $data['terpenuhi'] }} <i class="ti ti-check"></i></span>
                        <span class="badge bg-azure-lt" title="Melampaui">{{ $data['melampaui'] }} <i class="ti ti-rocket"></i></span>
                        <span class="badge bg-danger-lt" title="KTS">{{ $data['kts'] }} <i class="ti ti-alert-triangle"></i></span>
                    </div>
                </div>
            </button>
        </h2>
        <div id="collapse-{{ Str::slug($unitName) }}" class="accordion-collapse collapse" data-bs-parent="#accordion-units">
            <div class="accordion-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-sm m-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Indikator</th>
                                <th>Target</th>
                                <th>Capaian</th>
                                <th>AMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['indicators'] as $ind)
                            <tr>
                                <td class="text-muted">{{ $ind['no'] }}</td>
                                <td>{{ $ind['nama'] }}</td>
                                <td>{{ $ind['target'] }}</td>
                                <td>{{ $ind['capaian'] ?: '-' }}</td>
                                <td>
                                    @if($ind['ami'] === 1)
                                        <span class="badge bg-success-lt text-nowrap"><i class="ti ti-check me-1"></i> Terpenuhi</span>
                                    @elseif($ind['ami'] === 2)
                                        <span class="badge bg-azure-lt text-nowrap"><i class="ti ti-rocket me-1"></i> Melampaui</span>
                                    @elseif($ind['ami'] === 0)
                                        <span class="badge bg-danger-lt text-nowrap"><i class="ti ti-alert-triangle me-1"></i> KTS</span>
                                    @else
                                        <span class="badge bg-secondary-lt text-nowrap">Belum Evaluasi</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
