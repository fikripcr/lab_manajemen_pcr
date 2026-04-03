<div class="mb-4">
    <h3 class="mb-1">{{ $title }}</h3>
    @if($jenis)
    <span class="badge bg-primary-lt text-uppercase">{{ $jenis }}</span>
    @endif
    <span class="text-muted ms-2"><i class="ti ti-target me-1"></i> {{ $indicators->count() }} Indikator</span>
</div>

{{-- Overall Achievement Summary --}}
<div class="row row-cards mb-4">
    <div class="col-sm-3">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-primary text-white me-3">
                        <i class="ti ti-percentage fs-2"></i>
                    </span>
                    <div>
                        <div class="h2 mb-0 text-primary">{{ $overallRate }}%</div>
                        <div class="text-muted small">Ketercapaian</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    <div class="col-sm-3">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-success text-white me-3">
                        <i class="ti ti-checks fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-success">{{ $globalAmi['terpenuhi'] }}</div>
                        <div class="text-muted small">Terpenuhi</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    <div class="col-sm-3">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-azure text-white me-3">
                        <i class="ti ti-rocket fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-azure">{{ $globalAmi['melampaui'] }}</div>
                        <div class="text-muted small">Melampaui</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    <div class="col-sm-3">
        <x-tabler.card class="card-sm h-100">
            <x-tabler.card-body>
                <div class="d-flex align-items-center">
                    <span class="avatar bg-danger text-white me-3">
                        <i class="ti ti-alert-triangle fs-2"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-danger">{{ $globalAmi['kts'] }}</div>
                        <div class="text-muted small">KTS</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>

{{-- Indicator List --}}
<h4 class="mb-3"><i class="ti ti-list-details me-1"></i> Daftar Indikator ({{ count($indicatorDetails) }})</h4>

@if(empty($indicatorDetails))
<div class="text-center text-muted p-4 border rounded bg-light">
    <i class="ti ti-info-circle fs-2 mb-2 d-block"></i>
    Belum ada indikator yang terpetakan pada struktur turunan ini.
</div>
@else
<div class="accordion" id="accordion-indicators">
    @foreach($indicatorDetails as $idx => $ind)
    @php
        $collapseId = 'ind-' . Str::slug($ind['no'] . '-' . $idx);
        $achieved = $ind['ami']['terpenuhi'] + $ind['ami']['melampaui'];
        $rateColor = $ind['achievement_rate'] >= 80 ? 'success' : ($ind['achievement_rate'] >= 50 ? 'warning' : 'danger');
    @endphp
    <div class="accordion-item">
        <h2 class="accordion-header small" id="heading-{{ $collapseId }}">
            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $collapseId }}" aria-expanded="false">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary-lt text-nowrap ">{{ $ind['no'] }}</span>
                        <span class="text-truncate ">{{ $ind['nama'] }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3 flex-shrink-0 ">
                        <span class="badge bg-{{ $rateColor }}-lt text-nowrap">
                            <i class="ti ti-chart-pie me-1"></i>{{ $ind['achievement_rate'] }}%
                        </span>
                        <span class="text-muted text-nowrap">{{ $achieved }}/{{ $ind['total_units'] }}</span>
                        <span class="text-success text-nowrap"><i class="ti ti-check"></i> {{ $ind['ami']['terpenuhi'] }}</span>
                        <span class="text-azure text-nowrap"><i class="ti ti-rocket"></i> {{ $ind['ami']['melampaui'] }}</span>
                        <span class="text-danger text-nowrap"><i class="ti ti-alert-triangle"></i> {{ $ind['ami']['kts'] }}</span>
                        @if($ind['ami']['none'] > 0)
                            <span class="text-muted text-nowrap"><i class="ti ti-clock"></i> {{ $ind['ami']['none'] }}</span>
                        @endif
                    </div>
                </div>
            </button>
        </h2>
        <div id="collapse-{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordion-indicators">
            <div class="accordion-body p-3  bg-secondary-lt">


                {{-- Unit detail table --}}
                <div class="table-responsive">
                    <table class="table table-vcenter rounded bg-white">
                        <thead>
                            <tr>
                                <th class="small fw-bold">Unit</th>
                                <th class="small fw-bold">Target</th>
                                <th class="small fw-bold">Capaian</th>
                                <th class="small fw-bold">Hasil AMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ind['units'] as $unit)
                            <tr class="small">
                                <td class="fw-medium">{{ $unit['name'] }}</td>
                                <td>{{ $unit['target'] ?: '-' }}</td>
                                <td>{{ $unit['capaian'] ?: '-' }}</td>
                                <td>
                                    @if($unit['ami'] === 1)
                                        <span class="badge bg-success-lt text-nowrap"><i class="ti ti-check me-1"></i> Terpenuhi</span>
                                    @elseif($unit['ami'] === 2)
                                        <span class="badge bg-azure-lt text-nowrap"><i class="ti ti-rocket me-1"></i> Melampaui</span>
                                    @elseif($unit['ami'] === 0)
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
