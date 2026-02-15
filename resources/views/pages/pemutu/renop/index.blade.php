@extends('layouts.admin.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Renop Indicators</h3>
        <div class="card-actions">
            <a href="{{ route('pemutu.renop.create') }}" class="btn btn-primary">
                {{-- icon plus --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Add New Renop
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Indicator</th>
                    <th>Target</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($renops as $renop)
                <tr>
                    <td>{{ $renop->indikator }}</td>
                    <td>{{ $renop->target }}</td>
                    <td>
                        <a href="{{ route('pemutu.renop.assign', $renop->indikator_id) }}" class="btn btn-sm btn-secondary">
                            Assign
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
