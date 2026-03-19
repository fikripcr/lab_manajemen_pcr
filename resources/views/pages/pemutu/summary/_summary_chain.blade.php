{{-- Recursive chain rendering partial --}}
@php
    $jenisLabels = [
        'kebijakan' => ['label' => 'KEBIJAKAN', 'color' => 'red', 'icon' => 'ti-book'],
        'misi' => ['label' => 'MISI', 'color' => 'azure', 'icon' => 'ti-compass'],
        'rjp' => ['label' => 'RPJP', 'color' => 'indigo', 'icon' => 'ti-timeline'],
        'renstra' => ['label' => 'RENSTRA', 'color' => 'purple', 'icon' => 'ti-clipboard-list'],
        'renop' => ['label' => 'RENOP', 'color' => 'pink', 'icon' => 'ti-checklist'],
    ];
@endphp

@foreach($chain as $node)
@php
    $jenis = $node['jenis'] ?? '';
    $meta = $jenisLabels[$jenis] ?? ['label' => strtoupper($jenis), 'color' => 'secondary', 'icon' => 'ti-file'];
    $childPoin = $node['poin'];
    $childIndicators = $node['indicators'] ?? collect();
    $hasChain = !empty($node['chain']);
    $hasIndicators = $childIndicators->isNotEmpty();
@endphp
<div class="ms-{{ min($depth * 2, 6) }} mt-2">
    <div class="d-flex align-items-start gap-2 p-2 rounded border-start border-{{ $meta['color'] }} border-3 bg-{{ $meta['color'] }}-lt bg-opacity-10">
        <div class="flex-shrink-0">
            <span class="badge bg-{{ $meta['color'] }}-lt text-{{ $meta['color'] }} py-1 px-2">
                <i class="ti {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
            </span>
        </div>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-medium small">{{ $childPoin->judul }}</div>
            @if($childPoin->kode)
            <small class="text-muted">{{ $childPoin->kode }}</small>
            @endif
        </div>
    </div>

    {{-- Indicators for Renop points --}}
    @if($hasIndicators)
    <div class="ms-4 mt-2">
        @foreach($childIndicators as $ind)
        @php
            $parentInd = $ind->parent;
        @endphp
        <div class="border rounded p-2 mb-2 bg-white">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-teal-lt text-teal">
                    <i class="ti ti-target me-1"></i>{{ $ind->no_indikator }}
                </span>
                <span class="small fw-medium text-truncate">{{ $ind->indikator }}</span>
            </div>

            @if($parentInd)
            <div class="small text-muted mb-2">
                <i class="ti ti-arrow-right me-1"></i> Standar Induk:
                <span class="badge bg-secondary-lt">{{ $parentInd->no_indikator }}</span>
                {{ Str::limit($parentInd->indikator, 60) }}
            </div>
            @endif

            {{-- OrgUnit Results --}}
            @if($ind->orgUnits->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-vcenter mb-0 small">
                    <thead>
                        <tr>
                            <th class="w-40">Unit</th>
                            <th>Target</th>
                            <th>Capaian ED</th>
                            <th>Skala</th>
                            <th class="text-end" style="width:120px;">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ind->orgUnits as $ou)
                        @php
                            $edSkala = $ou->pivot->ed_skala;
                            $skalaArr = $ind->skala ?? [];
                            $skalaLabel = (is_array($skalaArr) && isset($skalaArr[$edSkala])) ? $skalaArr[$edSkala] : '-';
                            $maxIdx = count($skalaArr) - 1;
                            $score = ($edSkala !== null && $maxIdx > 0) ? round(($edSkala / $maxIdx) * 100) : null;

                            $scoreColor = 'secondary';
                            if ($score !== null) {
                                if ($score >= 80) $scoreColor = 'success';
                                elseif ($score >= 60) $scoreColor = 'primary';
                                elseif ($score >= 40) $scoreColor = 'warning';
                                else $scoreColor = 'danger';
                            }
                        @endphp
                        <tr>
                            <td class="fw-medium">{{ $ou->name }}</td>
                            <td>{{ $ou->pivot->target ?? '-' }}</td>
                            <td>{{ $ou->pivot->ed_capaian ?? '-' }}</td>
                            <td>
                                @if($skalaLabel !== '-')
                                <span class="badge bg-{{ $scoreColor }}-lt text-{{ $scoreColor }}">{{ $skalaLabel }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($score !== null)
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <div class="progress progress-sm flex-grow-1" style="max-width: 60px;">
                                        <div class="progress-bar bg-{{ $scoreColor }}" style="width: {{ $score }}%"></div>
                                    </div>
                                    <span class="fw-bold text-{{ $scoreColor }}">{{ $score }}%</span>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Continue chain recursion --}}
    @if($hasChain)
        @include('pemutu.summary._summary_chain', ['chain' => $node['chain'], 'depth' => $depth + 1])
    @endif
</div>
@endforeach
