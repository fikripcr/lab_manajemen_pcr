@extends('layouts.tabler.app')
@section('title', 'Evaluasi Diri - Pilih Periode')

@section('header')
<x-tabler.page-header title="Evaluasi Diri" pretitle="SPMI" />
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-12">
            <div class="card card-link card-link-pop">
                <a href="{{ route('pemutu.evaluasi-diri.show', $periode->encrypted_periodespmi_id) }}" class="d-block w-100 text-reset text-decoration-none">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-md rounded bg-blue-lt"><i class="ti ti-calendar-stats fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="card-title mb-1">Periode {{ $periode->periode }}</div>
                                <div class="text-muted">{{ $periode->jenis_periode }}</div>
                            </div>
                            <div class="col-auto">
                                @if($periode->ed_awal && $periode->ed_akhir)
                                    <span class="badge bg-green-lt">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $periode->ed_awal->format('d M') }} - {{ $periode->ed_akhir->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">Jadwal Belum Diatur</span>
                                @endif
                                @php $edPct = $edTotal > 0 ? round(($edFilled / $edTotal) * 100) : 0; @endphp
                                <span class="badge ms-2 {{ $edPct == 100 ? 'bg-green' : 'bg-secondary-lt' }}">
                                    <i class="ti ti-checklist me-1"></i>{{ $edFilled }}/{{ $edTotal }} Terisi
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
                title="Tidak Ada Periode Aktif" 
                text="Belum ada periode SPMI yang tersedia."
                icon="ti ti-calendar-off" 
            />
        </div>
    @endforelse
    
    <div class="d-flex justify-content-center mt-4">
        {{ $periodes->links() }}
    </div>
</div>
@endsection
