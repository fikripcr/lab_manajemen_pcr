@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Sasaran Kinerja Saya (My KPI)" pretitle="{{ $personil->nama ?? 'Pegawai' }}">
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <x-tabler.datatable-client 
        id="table-mykpi" 
        :columns="[
            ['name' => 'Periode'],
            ['name' => 'Indikator'],
            ['name' => 'Target'],
            ['name' => 'Realisasi'],
            ['name' => 'Skor'],
            ['name' => 'Status'],
            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
        ]"
    >
        @forelse($kpis as $kpi)
        <tr>
            <td>{{ $kpi->year }} ({{ $kpi->semester == 'Ganjil' ? 'Ganjil' : 'Genap' }})</td>
            <td>{{ $kpi->indikator->indikator }}</td>
            <td>{{ $kpi->target_value ?? $kpi->indikator->target }}</td>
            <td>{{ $kpi->realization ?? '-' }}</td>
            <td>{{ $kpi->score ?? '-' }}</td>
            <td>
                @if($kpi->status == 'approved')
                    <span class="badge bg-green text-white">Disetujui</span>
                @elseif($kpi->status == 'submitted')
                    <span class="badge bg-blue text-white">Diajukan</span>
                @else
                    <span class="badge bg-secondary text-white">Draft</span>
                @endif
            </td>
            <td class="text-end">
                <x-tabler.button href="{{ route('pemutu.mykpi.edit', $kpi->id) }}" class="btn-ghost-primary" size="sm" icon="ti ti-pencil" text="Update" />
            </td>
        </tr>
        @empty
        {{-- x-tabler.datatable-client will handle empty state if needed --}}
        @endforelse
    </x-tabler.datatable-client>

    @if($kpis->count() === 0)
        <x-tabler.empty-state 
            title="Belum ada indikator" 
            description="Belum ada indikator yang ditugaskan kepada Anda." 
            icon="ti ti-checklist"
        />
    @endif
</div>
@endsection
