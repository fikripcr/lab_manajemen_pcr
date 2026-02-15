@extends('layouts.admin.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Renop Indicators</h3>
        <div class="card-actions">
            <x-tabler.button href="{{ route('pemutu.renop.create') }}" style="primary" icon="ti ti-plus">
                Add New Renop
            </x-tabler.button>
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
                        <x-tabler.button href="#" style="secondary" size="sm">
                            Assign
                        </x-tabler.button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
