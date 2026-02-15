@extends('layouts.admin.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Standard & Performance Indicators</h3>
        <div class="card-actions">
            <a href="{{ route('pemutu.standar.create') }}" class="btn btn-primary">
                {{-- icon plus --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Add New Indicator
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Document / Sub</th>
                    <th>Indicator</th>
                    <th>Target</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($indikators as $ind)
                <tr>
                    <td>
                        @if($ind->type == 'standar')
                            <span class="badge bg-blue">Standard</span>
                        @else
                            <span class="badge bg-green">Performance</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;">
                            @foreach($ind->dokSubs as $ds)
                                <div class="text-muted small">{{ $ds->dokumen->judul ?? '-' }}</div>
                                <div>{{ $ds->judul }}</div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="text-wrap" style="max-width: 400px;">
                            {{ $ind->indikator }}
                        </div>
                    </td>
                    <td>{{ $ind->target }}</td>
                    <td>
                        <a href="{{ route('pemutu.standar.assign', $ind->indikator_id) }}" class="btn btn-sm btn-secondary">
                            Assign
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $indikators->links() }}
    </div>
</div>
@endsection
