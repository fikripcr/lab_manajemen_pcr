@props(['periode' => null, 'type', 'title' => null])

@php
    if (!$periode) {
        return;
    }

    // Get PPEPP Standard Configuration from Helper
    $ppepp = pemutuPpeppConfig($type);
    $phaseColor = $ppepp['color'];
    $phaseIcon = $ppepp['icon'];
    $phaseLabel = $ppepp['label'];
    $datePrefix = $ppepp['date_prefix'] ?? $type;

    $startField = $datePrefix . '_awal';
    $endField = $datePrefix . '_akhir';
    
    // Check if the fields exist on the model
    $start = $periode->$startField ?? null;
    $end = $periode->$endField ?? null;

    // Handle fallback for title
    $displayTitle = $title ?? match($type) {
        'penetapan', 'indikator', 'dokumen' => 'Masa Penetapan Standar',
        'ed' => 'Masa Evaluasi Diri (ED)',
        'ami' => 'Masa Audit Mutu Internal (AMI)',
        'te' => 'Masa Tinjauan Efektivitas (TE)',
        'rtp' => 'Masa Rencana Tindakan Perbaikan (RTP)',
        'ptp' => 'Masa Pelaksanaan Tindakan Perbaikan (PTP)',
        'pengendalian' => 'Masa Pengendalian Standar',
        'peningkatan' => 'Masa Peningkatan Mutu',
        'pelaksanaan' => 'Masa Pelaksanaan (Pemantauan)',
        default => "Masa $phaseLabel"
    };

    $status = pemutuPeriodeStatus($start, $end);
@endphp

<div {{ $attributes->merge(['class' => 'row mb-3 mt-n2']) }}>
    <div class="col-12">
        <div class="alert mb-0 py-2 px-3 border-0 shadow-none d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert" 
             style="font-size: 0.85rem; border-left: 4px solid var(--tblr-{{ $phaseColor }}, #{{ $phaseColor === 'azure' ? '206bc4' : '666' }}) !important; background-color: var(--tblr-bg-surface-secondary); background-image: linear-gradient(to right, var(--tblr-{{ $phaseColor }}-lt, transparent) 0%, transparent 100%); border-radius: 8px;">
            
            <div class="d-flex align-items-center">
                <div class="p-1 me-2 rounded-2 bg-{{ $phaseColor }}-lt d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="ti ti-{{ $phaseIcon }} text-{{ $phaseColor }}" style="font-size: 1.5rem;"></i>
                </div>
                
                <div class="ms-1">
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-{{ $phaseColor }} text-white p-0 d-flex align-items-center justify-content-center fw-bold" style="width: 18px; height: 18px; font-size: 0.6rem; border-radius: 4px;">{{ $ppepp['ppepp'] }}</span>
                        <span class="fw-bold text-{{ $phaseColor }}">{{ $displayTitle }}</span>
                    </div>
                    @if($start && $end)
                        <span class="text-secondary smaller">({{ $start->format('d M Y') }} — {{ $end->format('d M Y') }})</span>
                    @else
                        <span class="text-muted smaller fst-italic fw-medium">(Jadwal belum dikonfigurasi)</span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($status['is_active'])
                    <span class="status-indicator status-animated status-success me-2" title="Sedang Aktif">
                        <span class="status-indicator-circle"></span>
                        <span class="status-indicator-circle"></span>
                        <span class="status-indicator-circle"></span>
                    </span>
                @endif
                <span class="badge bg-{{ $status['color'] }}-lt border border-{{ $status['color'] }} border-opacity-10 px-3 py-1 font-weight-bold text-uppercase ls-1" style="font-size: 0.65rem;">
                    {{ $status['status_text'] }}
                </span>
            </div>
        </div>
    </div>
</div>
