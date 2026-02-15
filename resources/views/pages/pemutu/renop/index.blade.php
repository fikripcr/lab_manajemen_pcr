@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Indikator Rencana Operasional (Renop)" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.renop.create') }}" style="primary" icon="ti ti-plus" text="Tambah Renop" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th>Target</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($renops as $renop)
                <tr>
                    <td>{{ $renop->indikator }}</td>
                    <td>{{ $renop->target }}</td>
                    <td>
                        <x-tabler.button href="#" style="ghost-primary" size="sm" text="Assign" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
