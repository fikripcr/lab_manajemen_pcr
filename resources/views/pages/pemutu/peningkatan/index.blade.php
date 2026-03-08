@extends('layouts.tabler.app')
@section('title', 'Peningkatan')

@section('header')
<x-tabler.page-header title="Peningkatan" pretitle="SPMI / Peningkatan">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pemutu.pengendalian.index') }}" size="sm" text="Pengendalian" icon="ti ti-settings-check" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

@if($periodes->isEmpty())
    <x-tabler.empty-state
        title="Belum Ada Periode SPMI"
        text="Tambahkan periode SPMI terlebih dahulu sebelum melakukan Peningkatan."
        icon="ti ti-calendar-off"
    />
@else

<div class="row row-cards">
    @foreach($periodes as $periode)
        <div class="col-12">
            <x-tabler.card class="card-link card-link-pop">
                <a href="{{ route('pemutu.peningkatan.show', $periode->encrypted_periodespmi_id) }}" class="d-block w-100 text-reset text-decoration-none">
                    <x-tabler.card-body>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-md rounded bg-blue-lt"><i class="ti ti-trending-up fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="card-title mb-1">Periode {{ $periode->periode }}</div>
                                <div class="text-muted">{{ $periode->jenis_periode }}</div>
                            </div>
                            <div class="col-auto">
                                @if($periode->peningkatan_awal && $periode->peningkatan_akhir)
                                    <span class="badge bg-blue-lt">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $periode->peningkatan_awal->format('d M') }} - {{ $periode->peningkatan_akhir->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">Jadwal Belum Diatur</span>
                                @endif
                                <i class="ti ti-chevron-right ms-3 text-muted"></i>
                            </div>
                        </div>
                    </x-tabler.card-body>
                </a>
            </x-tabler.card>
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-4">
        {{ $periodes->links() }}
    </div>
</div>

@endif
@endsection
