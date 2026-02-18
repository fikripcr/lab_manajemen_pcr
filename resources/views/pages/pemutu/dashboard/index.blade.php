@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.periode-kpis.index') }}" class="btn-outline-primary" icon="ti ti-calendar" text="Kelola Periode KPI" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
{{-- Metrics Row --}}
<div class="row row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-primary-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-primary">Total Dokumen</div>
                </div>
                <div class="h1 mb-0 fw-bold text-primary">{{ $totalDokumen }}</div>
                <div class="text-primary opacity-50 small mt-2">Arsip Penjaminan Mutu</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-success-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-success">Total Indikator</div>
                </div>
                <div class="h1 mb-0 fw-bold text-success">{{ $totalIndikator }}</div>
                <div class="text-success opacity-50 small mt-2">Standar Mutu Internal</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-warning-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-warning">Total KPI</div>
                </div>
                <div class="h1 mb-0 fw-bold text-warning">{{ $totalKpi }}</div>
                <div class="text-warning fw-bold small mt-2">{{ $kpiAchievementRate }}% Submitted</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-purple-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-purple">Total Personil</div>
                </div>
                <div class="h1 mb-0 fw-bold text-purple">{{ $totalPersonil }}</div>
                <div class="text-purple opacity-50 small mt-2">Tim Penjaminan Mutu</div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline & Detailed Metrics --}}
<div class="row row-cards mt-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-history me-2 text-primary"></i> Timeline Periode SPMI</h3>
            </div>
            <div class="card-body">
                @if($activePeriodeSpmi)
                    <div class="mb-4 text-center p-3 bg-light rounded-3">
                        <div class="fw-bold fs-2 text-primary">{{ $activePeriodeSpmi->nama }}</div>
                        <div class="text-muted small">Periode: {{ $activePeriodeSpmi->periode }}</div>
                    </div>
                    
                    <ul class="steps steps-vertical">
                        @php
                            $phases = [
                                ['label' => 'Penetapan', 'date' => $activePeriodeSpmi->penetapan_awal, 'end' => $activePeriodeSpmi->penetapan_akhir],
                                ['label' => 'Pelaksanaan', 'date' => $activePeriodeSpmi->penetapan_akhir, 'end' => $activePeriodeSpmi->ami_awal],
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
                            @endphp
                            <li class="step-item {{ $isActive ? 'active' : '' }}">
                                <div class="h4 m-0 {{ $isActive ? 'text-primary' : '' }}">{{ $phase['label'] }}</div>
                                <div class="text-secondary small">
                                    {{ $start->format('d M Y') }} 
                                    @if($end) - {{ $end->format('d M Y') }} @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center py-5">
                        <i class="ti ti-calendar-off fs-1 opacity-25 d-block mb-3"></i>
                        Tidak ada periode SPMI yang aktif saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-transparent border-0 py-3">
                <h3 class="card-title fw-bold"><i class="ti ti-file-description me-2 text-primary"></i> Rincian Dokumen & Indikator</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-nowrap card-table">
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
                                <td rowspan="5" class="bg-light fw-bold text-dark">Kebijakan</td>
                                <td>Visi</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['visi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Misi</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['misi'] }}</span></td>
                            </tr>
                            <tr>
                                <td>RJP (Jangka Panjang)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['rjp'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Renstra (Strategis)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['renstra'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Renop (Operasional)</td>
                                <td class="text-end"><span class="badge bg-secondary-lt fw-bold">{{ $dokumenKebijakan['renop'] }}</span></td>
                            </tr>

                            {{-- Standar --}}
                            <tr>
                                <td rowspan="3" class="bg-blue-lt fw-bold text-primary">Standar</td>
                                <td>Standar SPMI</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['standar'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Manual Prosedur</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['manual_prosedur'] }}</span></td>
                            </tr>
                            <tr>
                                <td>Formulir</td>
                                <td class="text-end"><span class="badge bg-primary-lt fw-bold">{{ $dokumenStandar['formulir'] }}</span></td>
                            </tr>

                            {{-- Indikator --}}
                            <tr>
                                <td rowspan="3" class="bg-green-lt fw-bold text-success">Indikator</td>
                                <td>Indikator Standar</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $standarCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Renop</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $renopCount }}</span></td>
                            </tr>
                            <tr>
                                <td>Indikator Performa</td>
                                <td class="text-end"><span class="badge bg-success-lt fw-bold">{{ $performaCount }}</span></td>
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
                    <x-tabler.button href="{{ route('pemutu.rapat.create') }}" class="btn-primary" size="sm" icon="ti ti-plus" text="Buat Rapat" />
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
            <x-tabler.datatable-client
                id="table-recent-kpi"
                :columns="[
                    ['name' => 'Personil'],
                    ['name' => 'Indikator'],
                    ['name' => 'Periode'],
                    ['name' => 'Status']
                ]"
            >
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
            </x-tabler.datatable-client>
        </div>
    </div>
</div>
@endif
@endsection
