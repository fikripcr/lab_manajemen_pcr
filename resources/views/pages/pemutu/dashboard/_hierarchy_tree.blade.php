<div class="hierarchy-tree-container">
    @if($children->isEmpty() && $rootDokSubs->isEmpty())
        <div class="text-center py-4 text-muted border border-dashed rounded bg-light">
            <i class="ti ti-sitemap text-secondary" style="font-size: 2rem;"></i>
            <p class="mt-2 text-secondary mb-0">Tidak ada struktur turunan (Standar/Indikator) untuk Dokumen Visi ini pada periode aktif.</p>
        </div>
    @endif

    {{-- Render Children Dokumen (Standar Level) --}}
    @foreach($children as $child)
        <div class="card mb-3 shadow-sm border border-azure-lt border-opacity-50">
            <div class="card-header bg-azure-lt border-0 py-2">
                <h4 class="card-title w-100 d-flex justify-content-between align-items-center m-0">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-azure me-2">{{ $child->kode }}</span>
                        <div class="fw-bold fs-4 text-azure">{{ $child->judul }}</div>
                    </div>
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover table-sm m-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 50%;" class="ps-3">Sub Dokumen / Standar Kriteria</th>
                                <th class="text-center" style="width: 15%;">Unit Terkait</th>
                                <th class="text-center" style="width: 15%;">Rata-rata ED</th>
                                <th class="text-center" style="width: 20%;">Capaian AMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($child->dokSubs as $dokSub)
                                @php
                                    $indicatorsCount = 0;
                                    $totalEd = 0;
                                    $totalAmi = 0;
                                    $amiAchieved = 0;
                                    // Aggregate from orgUnits pivot
                                    foreach($dokSub->indikators as $ind) {
                                        foreach($ind->orgUnits as $ou) {
                                            $indicatorsCount++;
                                            $totalEd += (float) $ou->pivot->ed_skala;
                                            if ($ou->pivot->ami_hasil_akhir !== null) {
                                                $totalAmi++;
                                                if (in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $amiAchieved++;
                                            }
                                        }
                                    }
                                    $avgEd = $indicatorsCount > 0 ? number_format($totalEd / $indicatorsCount, 2) : '-';
                                    $amiPct = $totalAmi > 0 ? number_format(($amiAchieved / $totalAmi) * 100, 1) : '-';
                                @endphp
                                <tr data-bs-toggle="collapse" data-bs-target="#doksub-{{ $dokSub->doksub_id }}" class="cursor-pointer">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-start py-1">
                                            <i class="ti ti-chevron-down text-muted me-2 mt-1"></i>
                                            <div>
                                                <div class="fw-bold">{{ $dokSub->isi }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-secondary-lt px-2 py-1">{{ $indicatorsCount }} Assign</span></td>
                                    <td class="text-center fw-bold">{{ $avgEd }}</td>
                                    <td class="text-center">
                                        @if($amiPct !== '-')
                                            <span class="badge bg-{{ (float) $amiPct >= 80 ? 'success' : 'warning' }} px-2 py-1">{{ $amiPct }}%</span>
                                        @else
                                            <span class="text-muted fs-5">-</span>
                                        @endif
                                    </td>
                                </tr>
                                {{-- Expanded Indicators List --}}
                                <tr id="doksub-{{ $dokSub->doksub_id }}" class="collapse collapse-doksub bg-light border-0">
                                    <td colspan="4" class="p-0 border-0">
                                        <div class="p-3 border-start border-azure border-3 ms-2 mb-2 bg-white rounded-end shadow-sm">
                                            @if($dokSub->indikators->isEmpty())
                                                <div class="text-muted small fst-italic"><i class="ti ti-info-circle me-1"></i>Tidak ada indikator di dalam standar ini.</div>
                                            @else
                                                <div class="text-uppercase text-muted fw-bold mb-2 ps-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Breakdown Indikator</div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless table-vcenter m-0">
                                                        <tbody>
                                                            @foreach($dokSub->indikators as $ind)
                                                                <tr class="border-bottom border-light">
                                                                    <td class="ps-1 align-top py-2" style="width: 50%;">
                                                                        <div class="small">
                                                                            <span class="badge bg-dark-lt me-1">{{ $ind->kode }}</span> 
                                                                            {{ $ind->isi }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="align-top py-2" style="width: 50%;">
                                                                        @if($ind->orgUnits->isEmpty())
                                                                            <span class="text-muted small fst-italic">Belum di-assign ke unit</span>
                                                                        @else
                                                                            <ul class="list-unstyled m-0 small">
                                                                                @foreach($ind->orgUnits as $ou)
                                                                                    <li class="d-flex justify-content-between mb-1 pb-1 border-bottom border-light">
                                                                                        <span class="text-muted text-truncate w-50" title="{{ $ou->name }}">
                                                                                            <i class="ti ti-building me-1 opacity-50"></i>{{ $ou->name }}
                                                                                        </span>
                                                                                        <span class="text-end">
                                                                                            <span class="badge bg-blue-lt px-1 me-1" title="Evaluasi Diri">ED: {{ $ou->pivot->ed_skala ?? '-' }}</span>
                                                                                            @php
                                                                                                $stAmi = match((string)$ou->pivot->ami_hasil_akhir) {
                                                                                                    '2' => '<span class="text-success fw-bold">M</span>',
                                                                                                    '1' => '<span class="text-success fw-bold">TMS</span>',
                                                                                                    '0' => '<span class="text-danger fw-bold">TT</span>',
                                                                                                    default => '<span class="text-muted">-</span>'
                                                                                                };
                                                                                            @endphp
                                                                                            <span class="badge bg-light text-dark px-1 border" title="Hasil Akhir AMI">AMI: {!! $stAmi !!}</span>
                                                                                        </span>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($child->dokSubs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3 fst-italic">Standar/Sub ini masih kosong</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Render Direct DokSubs (If any exist directly on root) --}}
    @if($rootDokSubs->isNotEmpty())
        <div class="card mb-3 shadow-sm border border-teal-lt border-opacity-50">
            <div class="card-header bg-teal-lt border-0 py-2">
                <h4 class="card-title w-100 d-flex justify-content-between align-items-center m-0">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-layers-linked text-teal me-2 fs-2"></i>
                        <div class="fw-bold fs-4 text-teal">Sub Dokumen Langsung (Root)</div>
                    </div>
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover table-sm m-0">
                        {{-- Same structure as children table --}}
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 50%;" class="ps-3">Sub Dokumen / Standar Kriteria</th>
                                <th class="text-center" style="width: 15%;">Unit Terkait</th>
                                <th class="text-center" style="width: 15%;">Rata-rata ED</th>
                                <th class="text-center" style="width: 20%;">Capaian AMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rootDokSubs as $dokSub)
                                @php
                                    $indicatorsCount = 0;
                                    $totalEd = 0;
                                    $totalAmi = 0;
                                    $amiAchieved = 0;
                                    foreach($dokSub->indikators as $ind) {
                                        foreach($ind->orgUnits as $ou) {
                                            $indicatorsCount++;
                                            $totalEd += (float) $ou->pivot->ed_skala;
                                            if ($ou->pivot->ami_hasil_akhir !== null) {
                                                $totalAmi++;
                                                if (in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $amiAchieved++;
                                            }
                                        }
                                    }
                                    $avgEd = $indicatorsCount > 0 ? number_format($totalEd / $indicatorsCount, 2) : '-';
                                    $amiPct = $totalAmi > 0 ? number_format(($amiAchieved / $totalAmi) * 100, 1) : '-';
                                @endphp
                                <tr data-bs-toggle="collapse" data-bs-target="#doksub-root-{{ $dokSub->doksub_id }}" class="cursor-pointer">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-start py-1">
                                            <i class="ti ti-chevron-down text-muted me-2 mt-1"></i>
                                            <div>
                                                <div class="fw-bold">{{ $dokSub->isi }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-secondary-lt px-2 py-1">{{ $indicatorsCount }} Assign</span></td>
                                    <td class="text-center fw-bold">{{ $avgEd }}</td>
                                    <td class="text-center">
                                        @if($amiPct !== '-')
                                            <span class="badge bg-{{ (float) $amiPct >= 80 ? 'success' : 'warning' }} px-2 py-1">{{ $amiPct }}%</span>
                                        @else
                                            <span class="text-muted fs-5">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr id="doksub-root-{{ $dokSub->doksub_id }}" class="collapse collapse-doksub bg-light border-0">
                                    <td colspan="4" class="p-0 border-0">
                                        <div class="p-3 border-start border-teal border-3 ms-2 mb-2 bg-white rounded-end shadow-sm">
                                            @if($dokSub->indikators->isEmpty())
                                                <div class="text-muted small fst-italic"><i class="ti ti-info-circle me-1"></i>Tidak ada indikator di dalam standar ini.</div>
                                            @else
                                                <div class="text-uppercase text-muted fw-bold mb-2 ps-1" style="font-size: 0.65rem;">Breakdown Indikator</div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless table-vcenter m-0">
                                                        <tbody>
                                                            @foreach($dokSub->indikators as $ind)
                                                                <tr class="border-bottom border-light">
                                                                    <td class="ps-1 align-top py-2" style="width: 50%;">
                                                                        <div class="small"><span class="badge bg-dark-lt me-1">{{ $ind->kode }}</span> {{ $ind->isi }}</div>
                                                                    </td>
                                                                    <td class="align-top py-2" style="width: 50%;">
                                                                        @if($ind->orgUnits->isEmpty())
                                                                            <span class="text-muted small fst-italic">Belum di-assign ke unit</span>
                                                                        @else
                                                                            <ul class="list-unstyled m-0 small">
                                                                                @foreach($ind->orgUnits as $ou)
                                                                                    <li class="d-flex justify-content-between mb-1 pb-1 border-bottom border-light">
                                                                                        <span class="text-muted text-truncate w-50" title="{{ $ou->name }}">
                                                                                            <i class="ti ti-building me-1 opacity-50"></i>{{ $ou->name }}
                                                                                        </span>
                                                                                        <span class="text-end">
                                                                                            <span class="badge bg-blue-lt px-1 me-1">ED: {{ $ou->pivot->ed_skala ?? '-' }}</span>
                                                                                            @php
                                                                                                $stAmi = match((string)$ou->pivot->ami_hasil_akhir) {
                                                                                                    '2' => '<span class="text-success fw-bold">M</span>',
                                                                                                    '1' => '<span class="text-success fw-bold">TMS</span>',
                                                                                                    '0' => '<span class="text-danger fw-bold">TT</span>',
                                                                                                    default => '<span class="text-muted">-</span>'
                                                                                                };
                                                                                            @endphp
                                                                                            <span class="badge bg-light text-dark px-1 border">AMI: {!! $stAmi !!}</span>
                                                                                        </span>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
