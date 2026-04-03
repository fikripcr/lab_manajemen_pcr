@php
    /**
     * Helper to compute achievement from DokSub collection
     */
    $getStats = function($items) {
        $total = 0; $achieved = 0;
        // items can be a collection of dokumens or doksubs
        foreach($items as $item) {
            // If it's a Dokumen, we need to sum its children and doksubs
            if($item instanceof \App\Models\Pemutu\Dokumen) {
                // DokSubs of this doc
                foreach($item->dokSubs as $ds) {
                    foreach($ds->indikators as $ind) {
                        foreach($ind->orgUnits as $ou) {
                            $total++;
                            if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $achieved++;
                        }
                    }
                }
                // Plus recursively from children if any (deep depth)
                // (For simplicity we handle first level of indicators here)
            } else {
                // It's a DokSub
                foreach($item->indikators as $ind) {
                    foreach($ind->orgUnits as $ou) {
                        $total++;
                        if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $achieved++;
                    }
                }
            }
        }
        $pct = $total > 0 ? ($achieved / $total) * 100 : 0;
        return ['pct' => $pct, 'total' => $total];
    };

    /**
     * Helper to render badge
     */
    $renderBadge = function($pct, $total) {
        if ($total == 0) return '<span class="status-badge-premium bg-light text-muted border">Empty</span>';
        
        $status = 'Critical'; $color = 'danger';
        if ($pct == 100) { $status = 'Done'; $color = 'success'; }
        elseif ($pct >= 90) { $status = 'Optimal'; $color = 'success'; }
        elseif ($pct >= 75) { $status = 'Steady'; $color = 'azure'; }
        elseif ($pct >= 50) { $status = 'Progress'; $color = 'warning'; }
        
        return '<span class="status-badge-premium bg-'.$color.'-lt text-'.$color.' border border-'.$color.'-lt">'.number_format($pct, 1).'% '.$status.'</span>';
    };

    // Calculate Root Stats
    // We need a more robust way to aggregate all indicators under the root
    $rootFullStats = ['total' => 0, 'achieved' => 0];
    // Start with root's direct doksubs
    foreach($root->dokSubs as $ds) {
        foreach($ds->indikators as $ind) {
            foreach($ind->orgUnits as $ou) {
                $rootFullStats['total']++;
                if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $rootFullStats['achieved']++;
            }
        }
    }
    // Then all children
    foreach($root->children as $child) {
        foreach($child->dokSubs as $ds) {
            foreach($ds->indikators as $ind) {
                foreach($ind->orgUnits as $ou) {
                    $rootFullStats['total']++;
                    if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $rootFullStats['achieved']++;
                }
            }
        }
    }
    $rootPct = $rootFullStats['total'] > 0 ? ($rootFullStats['achieved'] / $rootFullStats['total']) * 100 : 0;
@endphp

<div class="roadmap-container">
    {{-- LEVEL 1 — IDENTITY --}}
    <div class="roadmap-item">
        <div class="roadmap-branch" style="top: 30px;"></div>
        <div class="roadmap-card p-3 border-start border-primary border-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="status-icon-box bg-primary-lt text-primary me-3" style="width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-eye fs-2"></i>
                    </div>
                    <div>
                        <div class="text-uppercase text-primary fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05rem;">LEVEL 1 — IDENTITY</div>
                        <h3 class="mb-0 fw-bold">{{ $root->judul }}</h3>
                    </div>
                </div>
                <div>
                   {!! $renderBadge($rootPct, $rootFullStats['total']) !!}
                </div>
            </div>
        </div>

        {{-- LEVEL 2 — MISSION --}}
        @if($children->isNotEmpty())
            <div class="mt-4 ms-4 ps-2">
                @foreach($children as $child)
                    @php
                        $childStats = ['total' => 0, 'achieved' => 0];
                        foreach($child->dokSubs as $ds) {
                            foreach($ds->indikators as $ind) {
                                foreach($ind->orgUnits as $ou) {
                                    $childStats['total']++;
                                    if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $childStats['achieved']++;
                                }
                            }
                        }
                        // Plus grand children
                        foreach($child->children as $gc) {
                             foreach($gc->dokSubs as $ds) {
                                foreach($ds->indikators as $ind) {
                                    foreach($ind->orgUnits as $ou) {
                                        $childStats['total']++;
                                        if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $childStats['achieved']++;
                                    }
                                }
                            }
                        }
                        $childPct = $childStats['total'] > 0 ? ($childStats['achieved'] / $childStats['total']) * 100 : 0;
                    @endphp

                    <div class="roadmap-item mb-4">
                        <div class="roadmap-branch" style="left: -32px; top: 24px; bottom: -12px;"></div>
                        <div class="roadmap-line-horizontal"></div>
                        
                        <div class="roadmap-card p-3 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center overflow-hidden">
                                    <div class="status-icon-box bg-azure-lt text-azure me-3" style="width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="ti ti-rocket fs-3"></i>
                                    </div>
                                    <div>
                                        <div class="text-uppercase text-azure fw-bold" style="font-size: 0.6rem; letter-spacing: 0.05rem;">LEVEL 2 — MISSION</div>
                                        <h4 class="mb-0 fw-bold text-truncate">{{ $child->judul }}</h4>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    {!! $renderBadge($childPct, $childStats['total']) !!}
                                </div>
                            </div>

                            {{-- LEVEL 3 — RPJP / GRANDCHILDREN OR STANDAR --}}
                            @if($child->children->isNotEmpty() || $child->dokSubs->isNotEmpty())
                                <div class="mt-4 ms-4 ps-2 border-start">
                                    {{-- Render Sub Dokumens first if any (Recursion-like) --}}
                                    @foreach($child->children as $gc)
                                        @php
                                            $gcStats = ['total' => 0, 'achieved' => 0];
                                            foreach($gc->dokSubs as $ds) {
                                                foreach($ds->indikators as $ind) {
                                                    foreach($ind->orgUnits as $ou) {
                                                        $gcStats['total']++;
                                                        if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $gcStats['achieved']++;
                                                    }
                                                }
                                            }
                                            $gcPct = $gcStats['total'] > 0 ? ($gcStats['achieved'] / $gcStats['total']) * 100 : 0;
                                        @endphp
                                        <div class="roadmap-item mb-3">
                                            <div class="roadmap-branch" style="left: -32px; top: 20px; bottom: -12px;"></div>
                                            <div class="roadmap-line-horizontal"></div>
                                            <div class="roadmap-card p-2 shadow-sm border-dashed">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center overflow-hidden">
                                                        <div class="avatar bg-light text-dark me-2 avatar-sm"><i class="ti ti-calendar"></i></div>
                                                        <div class="text-truncate">
                                                            <div class="text-uppercase text-muted fw-bold" style="font-size: 0.55rem;">LEVEL 3 — RPJP / DOKUMEN</div>
                                                            <div class="fw-bold small text-truncate" title="{{ $gc->judul }}">{{ $gc->judul }}</div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        {!! $renderBadge($gcPct, $gcStats['total']) !!}
                                                    </div>
                                                </div>
                                                
                                                {{-- RENSTRA / STANDAR Level 4 --}}
                                                @if($gc->dokSubs->isNotEmpty())
                                                    <div class="mt-2 ms-4">
                                                        @foreach($gc->dokSubs as $ds)
                                                            @php
                                                                $dsStats = ['total' => 0, 'achieved' => 0];
                                                                foreach($ds->indikators as $ind) {
                                                                    foreach($ind->orgUnits as $ou) {
                                                                        $dsStats['total']++;
                                                                        if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $dsStats['achieved']++;
                                                                    }
                                                                }
                                                                $dsPct = $dsStats['total'] > 0 ? ($dsStats['achieved'] / $dsStats['total']) * 100 : 0;
                                                            @endphp
                                                            <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light">
                                                                <div class="d-flex align-items-center overflow-hidden">
                                                                    <span class="p-1 bg-{{ $dsPct >= 90 ? 'success' : ($dsPct >= 50 ? 'azure' : 'danger') }} rounded-circle me-3" style="width: 8px; height: 8px;"></span>
                                                                    <div class="text-truncate">
                                                                        <div class="text-uppercase text-muted fw-bold" style="font-size: 0.5rem;">RENSTRA / STANDAR</div>
                                                                        <div class="small fw-bold text-truncate" style="max-width: 200px;" title="{{ $ds->isi }}">{{ $ds->isi }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 d-flex align-items-center">
                                                                    {!! $renderBadge($dsPct, $dsStats['total']) !!}
                                                                    <a href="#" class="btn btn-sm btn-ghost-primary p-1 border-0 ms-2" title="Lihat Indikator">
                                                                        <i class="ti ti-arrow-right"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Renstra/Standar directly under Mission Level 2 --}}
                                    @foreach($child->dokSubs as $dokSub)
                                        @php
                                            $dsStats = ['total' => 0, 'achieved' => 0];
                                            foreach($dokSub->indikators as $ind) {
                                                foreach($ind->orgUnits as $ou) {
                                                    $dsStats['total']++;
                                                    if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $dsStats['achieved']++;
                                                }
                                            }
                                            $dsPct = $dsStats['total'] > 0 ? ($dsStats['achieved'] / $dsStats['total']) * 100 : 0;
                                        @endphp
                                        <div class="roadmap-item mb-2">
                                            <div class="roadmap-branch" style="left: -32px; top: 18px; bottom: -12px;"></div>
                                            <div class="roadmap-line-horizontal"></div>
                                            <div class="roadmap-card p-2 shadow-none border-dashed bg-light-lt">
                                                 <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center overflow-hidden">
                                                        <div class="avatar bg-white text-azure shadow-sm me-2 avatar-sm"><i class="ti ti-target fs-3"></i></div>
                                                        <div class="text-truncate">
                                                            <div class="text-uppercase text-muted fw-bold" style="font-size: 0.55rem;">RENSTRA / STANDAR</div>
                                                            <div class="fw-bold small text-truncate" title="{{ $dokSub->isi }}">{{ $dokSub->isi }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        {!! $renderBadge($dsPct, $dsStats['total']) !!}
                                                        <a href="#" class="btn btn-sm btn-ghost-primary p-0 border-0 ms-2" title="Lihat Rincian">
                                                            <i class="ti ti-arrow-right fs-3"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Level 2: Direct Standards on Root (Fallback for very flat structures) --}}
    @if($rootDokSubs->isNotEmpty())
        <div class="mt-3 ms-4 ps-2 border-start">
            @foreach($rootDokSubs as $ds)
                @php
                    $dsStats = ['total' => 0, 'achieved' => 0];
                    foreach($ds->indikators as $ind) {
                        foreach($ind->orgUnits as $ou) {
                            $dsStats['total']++;
                            if(in_array((int) $ou->pivot->ami_hasil_akhir, [1,2])) $dsStats['achieved']++;
                        }
                    }
                    $dsPct = $dsStats['total'] > 0 ? ($dsStats['achieved'] / $dsStats['total']) * 100 : 0;
                @endphp
                <div class="roadmap-item mb-3">
                    <div class="roadmap-branch" style="left: -32px; top: 20px; bottom: -12px;"></div>
                    <div class="roadmap-line-horizontal"></div>
                    <div class="roadmap-card p-2 bg-light-lt">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-layers-linked text-azure me-3 fs-3"></i>
                                <div>
                                    <div class="text-uppercase text-azure fw-bold" style="font-size: 0.55rem;">DIRECT STANDAR</div>
                                    <div class="fw-bold small">{{ $ds->isi }}</div>
                                </div>
                            </div>
                            <span class="badge bg-azure-lt">{{ number_format($dsPct, 1) }}% Done</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
