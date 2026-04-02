@props([
    'skala' => null,
    'max' => 4,
    'showLabel' => false,
    'labels' => [],
    'size' => 'md', {{-- sm, md --}}
    'variant' => 'badge' {{-- badge, dot, text --}}
])

@php
    $score = ($skala !== null && $skala !== '') ? (int)$skala : null;
    
    $color = 'secondary';
    if ($score !== null) {
        if ($score >= 4) $color = 'green';
        elseif ($score >= 3) $color = 'blue';
        elseif ($score >= 2) $color = 'orange';
        elseif ($score >= 1) $color = 'red';
        else $color = 'secondary';
    }

    $label = '-';
    if ($score !== null && !empty($labels) && isset($labels[$score])) {
        $label = $labels[$score];
    }

    $badgeSize = $size === 'sm' ? 'badge-sm' : '';
    $fontSize = $size === 'sm' ? 'font-size: 10px;' : 'font-size: 11px;';
@endphp

@if($score !== null)
    <div class="d-inline-flex align-items-center gap-1 skala-indicator-wrapper" title="Skala: {{ $score }}" data-bs-toggle="tooltip">
        @if($variant === 'dot')
            <span class="status-dot status-{{ $color }} me-1"></span>
            <span class="fw-bold text-{{ $color }}" style="{{ $fontSize }}">Skala {{ $score }}</span>
        @elseif($variant === 'text')
             <span class="fw-bold text-{{ $color }}" style="{{ $fontSize }}">[{{ $score }}]</span>
        @else
            <span class="badge bg-{{ $color }}-lt text-{{ $color }} {{ $badgeSize }} border border-{{ $color }} border-opacity-10 fw-bold px-2">
                Skala {{ $score }}
            </span>
        @endif

        @if($showLabel && $label !== '-')
            <small class="text-muted text-truncate d-none d-md-inline" style="max-width: 150px;">{{ strip_tags($label) }}</small>
        @endif
    </div>
@else
    <span class="text-muted-light small fst-italic">n/a</span>
@endif
