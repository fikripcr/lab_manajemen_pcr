@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Indikator Standar & Performa" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.standar.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Indikator" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <x-tabler.datatable-client 
        id="table-indikator" 
        :columns="[
            ['name' => 'Tipe'],
            ['name' => 'Dokumen / Sub'],
            ['name' => 'Indikator'],
            ['name' => 'Target'],
            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
        ]"
    >
        @foreach($indikators as $ind)
        <tr>
            <td>
                @if($ind->type == 'standar')
                    <span class="badge bg-blue text-white">Standar</span>
                @else
                    <span class="badge bg-green text-white">Performa</span>
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
            <td class="text-end">
                <x-tabler.button href="{{ route('pemutu.standar.assign', $ind->indikator_id) }}" class="btn-ghost-primary" size="sm" icon="ti ti-user-plus" text="Assign" />
            </td>
        </tr>
        @endforeach
    </x-tabler.datatable-client>

    @if($indikators->hasPages())
    <div class="card-footer d-flex align-items-center">
        {{ $indikators->links() }}
    </div>
    @endif
</div>
@endsection
