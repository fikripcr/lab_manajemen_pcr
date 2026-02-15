@extends('layouts.admin.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <a href="{{ route('pemutu.periode-kpis.index') }}" class="btn btn-outline-primary">
            <i class="ti ti-calendar me-2"></i> Kelola Periode KPI
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
{{-- Metrics Row --}}
<div class="row row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Dokumen</div>
                </div>
                <div class="h1 mb-0">{{ $totalDokumen }}</div>
                {{-- Breakdown removed --}}
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Indikator</div>
                </div>
                <div class="h1 mb-0">{{ $totalIndikator }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total KPI</div>
                </div>
                <div class="h1 mb-0">{{ $totalKpi }}</div>
                <div class="text-secondary mt-1">{{ $kpiAchievementRate }}% Submitted</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Personil</div>
                </div>
                <div class="h1 mb-0">{{ $totalPersonil }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline & Detailed Metrics --}}
<div class="row row-cards mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline Periode SPMI</h3>
            </div>
            <div class="card-body">
                @if($activePeriodeSpmi)
                    <div class="mb-3">
                        <div class="fw-bold fs-3">{{ $activePeriodeSpmi->nama }} ({{ $activePeriodeSpmi->periode }})</div>
                    </div>
                    
                    <ul class="steps steps-vertical">
                        @php
                            $phases = [
                                ['label' => 'Penetapan', 'date' => $activePeriodeSpmi->penetapan_awal, 'end' => $activePeriodeSpmi->penetapan_akhir],
                                ['label' => 'Pelaksanaan', 'date' => $activePeriodeSpmi->penetapan_akhir, 'end' => $activePeriodeSpmi->ami_awal], // Assuming flow gap
                                ['label' => 'Evaluasi (AMI)', 'date' => $activePeriodeSpmi->ami_awal, 'end' => $activePeriodeSpmi->ami_akhir],
                                ['label' => 'Pengendalian', 'date' => $activePeriodeSpmi->pengendalian_awal, 'end' => $activePeriodeSpmi->pengendalian_akhir],
                                ['label' => 'Peningkatan', 'date' => $activePeriodeSpmi->peningkatan_awal, 'end' => $activePeriodeSpmi->peningkatan_akhir],
                            ];
                            $now = now();
                        @endphp
                        
                        @foreach($phases as $phase)
                            @php
                                $start = \Carbon\Carbon::parse($phase['date']);
                                $end = $phase['end'] ? \Carbon\Carbon::parse($phase['end']) : null;
                                $isActive = $now->between($start, $end ?? $start->copy()->addDay());
                                $isPast = $now->gt($end ?? $start);
                                $statusClass = $isActive ? 'step-item active' : ($isPast ? 'step-item' : 'step-item text-muted'); // Tabler step classes: active for current
                                // Actually Tabler steps: step-item active (current), step-item (completed/past is just default?), step-item disabled?
                                // Let's use simple logic: Active gets blue dot.
                            @endphp
                            <li class="step-item {{ $isActive ? 'active' : '' }}">
                                <div class="h4 m-0">{{ $phase['label'] }}</div>
                                <div class="text-secondary small">
                                    {{ $start->format('d M Y') }} 
                                    @if($end) - {{ $end->format('d M Y') }} @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center py-4">
                        Tidak ada periode SPMI yang aktif saat ini.
                    </div>
                @endif
                
                @if($activePeriodeKpi)
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Periode KPI Aktif:</strong> {{ $activePeriodeKpi->nama }}
                        </div>
                        <div class="text-muted">
                            {{ $activePeriodeKpi->tanggal_mulai->format('d M') }} - {{ $activePeriodeKpi->tanggal_selesai->format('d M Y') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rincian Dokumen & Indikator</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Kebijakan --}}
                            <tr>
                                <td rowspan="5" class="bg-light-lt"><strong>Kebijakan</strong></td>
                                <td>Visi</td>
                                <td class="text-end"><span class="badge bg-secondary-lt">{{ $dokumenKebijakan['visi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Misi</td>
                                <td class="text-end"><span class="badge bg-secondary-lt">{{ $dokumenKebijakan['misi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>RJP (Jangka Panjang)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt">{{ $dokumenKebijakan['rjp'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Renstra (Strategis)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt">{{ $dokumenKebijakan['renstra'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Renop (Operasional)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt">{{ $dokumenKebijakan['renop'] }}</span></td>
                            </tr>

                            {{-- Standar --}}
                            <tr>
                                <td rowspan="3" class="bg-blue-lt"><strong>Standar</strong></td>
                                <td>Standar SPMI</td>
                                <td class="text-end"><span class="badge bg-primary-lt">{{ $dokumenStandar['standar'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Manual Prosedur</td>
                                <td class="text-end"><span class="badge bg-primary-lt">{{ $dokumenStandar['manual_prosedur'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Formulir</td>
                                <td class="text-end"><span class="badge bg-primary-lt">{{ $dokumenStandar['formulir'] }}</span></td>
                            </tr>

                            {{-- Indikator --}}
                            <tr>
                                <td rowspan="3" class="bg-green-lt"><strong>Indikator</strong></td>
                                <td>Indikator Standar</td>
                                <td class="text-end"><span class="badge bg-success-lt">{{ $standarCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Renop</td>
                                <td class="text-end"><span class="badge bg-success-lt">{{ $renopCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Performa</td>
                                <td class="text-end"><span class="badge bg-success-lt">{{ $performaCount }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

{{-- Jadwal Rapat --}}
<div class="row row-cards mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jadwal Rapat Mendatang</h3>
                <div class="card-actions">
                    <a href="{{ route('pemutu.rapat.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Buat Rapat
                    </a>
                </div>
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse($upcomingRapats as $rapat)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-blue-lt">{{ $rapat->tgl_rapat->format('d') }}<br><small>{{ $rapat->tgl_rapat->format('M') }}</small></span>
                            </div>
                            <div class="col text-truncate">
                                <a href="{{ route('pemutu.rapat.show', $rapat->hashid) }}" class="text-reset d-block">{{ $rapat->judul_kegiatan }}</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    {{ $rapat->jenis_rapat }} &bull; {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('pemutu.rapat.show', $rapat->hashid) }}" class="list-group-item-actions">
                                    <i class="ti ti-chevron-right text-muted"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item">
                        <div class="text-muted text-center py-3">Tidak ada jadwal rapat mendatang.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Recent KPI Submissions --}}
@if($recentKpi->count() > 0)
<div class="row row-cards mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengajuan KPI Terbaru</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Indikator</th>
                            <th>Periode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentKpi as $kpi)
                        <tr>
                            <td>{{ $kpi->personil->nama ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 300px;">{{ $kpi->indikator->indikator ?? '-' }}</td>
                            <td>{{ $kpi->semester }} {{ $kpi->year }}</td>
                            <td>
                                <span class="badge bg-{{ $kpi->status === 'approved' ? 'success' : 'info' }}-lt">
                                    {{ ucfirst($kpi->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
