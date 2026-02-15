@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    My KPI / SKP List
                </h2>
                <div class="text-muted mt-1">{{ $personil->nama ?? 'Unknown' }}</div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Indicator</th>
                            <th>Target</th>
                            <th>Realization</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kpis as $kpi)
                        <tr>
                            <td>{{ $kpi->year }} ({{ $kpi->semester == 1 ? 'Ganjil' : 'Genap' }})</td>
                            <td>{{ $kpi->indikator->indikator }}</td>
                            <td>{{ $kpi->target_value ?? $kpi->indikator->target }}</td>
                            <td>{{ $kpi->realization ?? '-' }}</td>
                            <td>{{ $kpi->score ?? '-' }}</td>
                            <td>
                                @if($kpi->status == 'approved')
                                    <span class="badge bg-green">Approved</span>
                                @elseif($kpi->status == 'submitted')
                                    <span class="badge bg-blue">Submitted</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                <x-tabler.button href="{{ route('pemutu.mykpi.edit', $kpi->id) }}" style="primary" size="sm">
                                    Update
                                </x-tabler.button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No Indicators Assigned</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
