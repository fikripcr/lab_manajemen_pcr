@extends('layouts.tabler.app')
@section('title', 'Pengendalian Indikator')

@section('header')
<x-tabler.page-header title="Pengendalian Indikator" pretitle="SPMI / Pengendalian">
    <x-slot:actions>
        <a href="{{ route('pemutu.ami.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-zoom-scan me-1"></i> AMI
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

@if($periodes->isEmpty())
    <x-tabler.empty-state
        title="Belum Ada Periode SPMI"
        text="Tambahkan periode SPMI terlebih dahulu sebelum melakukan Pengendalian."
        icon="ti ti-calendar-off"
    />
@else

<div class="row row-cards">
    @foreach($periodes as $periode)
        <div class="col-12">
            <div class="card card-link card-link-pop">
                <a href="{{ route('pemutu.pengendalian.show', $periode->encrypted_periodespmi_id) }}" class="d-block w-100 text-reset text-decoration-none">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-md rounded bg-teal-lt"><i class="ti ti-settings-check fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="card-title mb-1">Periode {{ $periode->periode }}</div>
                                <div class="text-muted">{{ $periode->jenis_periode }}</div>
                            </div>
                            <div class="col-auto">
                                @if($periode->pengendalian_awal && $periode->pengendalian_akhir)
                                    <span class="badge bg-teal-lt">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $periode->pengendalian_awal->format('d M') }} - {{ $periode->pengendalian_akhir->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">Jadwal Belum Diatur</span>
                                @endif
                                <i class="ti ti-chevron-right ms-3 text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-4">
        {{ $periodes->links() }}
    </div>
</div>

@endif
@endsection
