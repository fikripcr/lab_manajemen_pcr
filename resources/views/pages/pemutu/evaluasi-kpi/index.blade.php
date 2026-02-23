@extends('layouts.tabler.app')
@section('title', 'Evaluasi KPI - Pilih Periode')

@section('header')
<x-tabler.page-header title="Evaluasi KPI" pretitle="Pegawai">
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-12">
            <div class="card card-link card-link-pop">
                <a href="{{ route('pemutu.evaluasi-kpi.show', $periode->encrypted_periode_kpi_id) }}" class="d-block w-100 text-reset text-decoration-none">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-md rounded bg-primary-lt"><i class="ti ti-calendar-stats fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="card-title mb-1">{{ $periode->nama }}</div>
                                <div class="text-muted">{{ $periode->tahun }} / {{ $periode->semester }}</div>
                            </div>
                            <div class="col-auto">
                                @if($periode->is_active)
                                    <span class="badge bg-green-lt me-2">
                                        <i class="ti ti-circle-check me-1"></i> Aktif
                                    </span>
                                @endif
                                @if($periode->tanggal_mulai && $periode->tanggal_selesai)
                                    <span class="badge bg-azure-lt">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $periode->tanggal_mulai->format('d M') }} - {{ $periode->tanggal_selesai->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">Jadwal Belum Diatur</span>
                                @endif
                                @php
                                    $total  = $totalCounts[$periode->periode_kpi_id] ?? 0;
                                    $filled = $filledCounts[$periode->periode_kpi_id] ?? 0;
                                    $pct    = $total > 0 ? round(($filled / $total) * 100) : 0;
                                @endphp
                                <span class="badge ms-2 {{ $pct == 100 ? 'bg-green' : ($pct > 0 ? 'bg-yellow' : 'bg-secondary-lt') }}">
                                    <i class="ti ti-checklist me-1"></i>{{ $filled }}/{{ $total }} Terisi
                                </span>
                                <i class="ti ti-chevron-right ms-3 text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <x-tabler.empty-state 
                title="Tidak Ada Periode KPI" 
                description="Belum ada periode KPI yang tersedia saat ini." 
                icon="ti ti-calendar-off" 
            />
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $periodes->links() }}
    </div>
</div>
@endsection
