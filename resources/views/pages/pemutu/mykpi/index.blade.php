@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Sasaran Kinerja Saya (My KPI)" pretitle="{{ $personil->nama ?? 'Pegawai' }}">
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Indikator</th>
                    <th>Target</th>
                    <th>Realisasi</th>
                    <th>Skor</th>
                    <th>Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kpis as $kpi)
                <tr>
                    <td>{{ $kpi->year }} ({{ $kpi->semester == 'Ganjil' ? 'Ganjil' : 'Genap' }})</td>
                    <td>{{ $kpi->indikator->indikator }}</td>
                    <td>{{ $kpi->target_value ?? $kpi->indikator->target }}</td>
                    <td>{{ $kpi->realization ?? '-' }}</td>
                    <td>{{ $kpi->score ?? '-' }}</td>
                    <td>
                        @if($kpi->status == 'approved')
                            <span class="badge bg-green">Disetujui</span>
                        @elseif($kpi->status == 'submitted')
                            <span class="badge bg-blue">Diajukan</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td>
                        <x-tabler.button href="{{ route('pemutu.mykpi.edit', $kpi->id) }}" style="ghost-primary" size="sm" icon="ti ti-pencil" text="Update" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada indikator yang ditugaskan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
